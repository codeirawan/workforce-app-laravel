<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Controller;
use App\Models\Forecast\Calculation;
use App\Models\Master\ShiftSkill;
use App\Models\Schedule\SchedulePeriod;
use App\Models\Schedule\ScheduleShift;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Forecast\Params;
use Laratrust, DB, DataTables, Lang;

class ScheduleController extends Controller
{
    public function index()
    {
        if (!Laratrust::isAbleTo('view-schedule')) {
            return abort(404);
        }

        return view('schedule.index');
    }

    public function data()
    {
        if (!Laratrust::isAbleTo('view-forecast')) {
            return abort(404);
        }

        $scheduling = SchedulePeriod::select(
            'schedule_period.id',
            'schedule_period.forecast_id',
            'params.week',
            'params.start_date',
            'params.end_date',
            'master_cities.name AS site',
            'master_projects.name AS project',
            'master_skills.name AS skill',
        )
            ->leftJoin('params', 'params.id', '=', 'schedule_period.forecast_id')
            ->leftJoin('master_cities', 'master_cities.id', '=', 'params.city_id')
            ->leftJoin('master_projects', 'master_projects.id', '=', 'params.project_id')
            ->leftJoin('master_skills', 'master_skills.id', '=', 'params.skill_id')
            ->whereNull('params.deleted_at')
            ->get();

        return DataTables::of($scheduling)
            ->addColumn('action', function ($row) {
                $view = '<a href="' . route('schedule.show', $row->id) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('View') . '"><i class="fa-solid fa-sm fa-eye"></i></a>';

                return (Laratrust::isAbleTo('view-forecast') ? $view : '');
            })
            ->rawColumns(['action'])
            ->make(true);

    }

    public function show($id)
    {
        if (!Laratrust::isAbleTo('view-forecast')) {
            return abort(404);
        }

        $scheduling = SchedulePeriod::select(
            'schedule_period.id',
            'schedule_period.forecast_id',
            'master_skills.id AS skill_id',
            'master_skills.name AS skill',
            'params.reporting_period',
        )
            ->leftJoin('params', 'params.id', '=', 'schedule_period.forecast_id')
            ->leftJoin('master_skills', 'master_skills.id', '=', 'params.skill_id')
            ->whereNull('params.deleted_at')
            ->where('schedule_period.id', '=', $id)
            ->firstOrFail();

        $shifts = ShiftSkill::select(
            'master_shift.id',
            'master_shift.name',
            'master_shift.start_time',
            'master_shift.end_time'
        )
            ->where('skill_id', '=', $scheduling->skill_id)
            ->leftJoin('master_shift', 'master_shift.id', '=', 'shift_skills.shift_id')
            ->get();

        $availableAgents = User::select(
            'users.id',
            'users.nik',
            'users.name',
            'users.gender',
            'users.religion',
            'users.skill_id',
            'master_skills.name AS skill_name',
        )
            ->where('skill_id', '=', $scheduling->skill_id)
            ->leftJoin('master_skills', 'master_skills.id', '=', 'users.skill_id')
            ->get();

        // List of days of the week
        $daysOfWeek = [
            'mon' => 'Monday',
            'tue' => 'Tuesday',
            'wed' => 'Wednesday',
            'thu' => 'Thursday',
            'fri' => 'Friday',
            'sat' => 'Saturday',
            'sun' => 'Sunday'
        ];

        // Initialize an array to store forecast data for all days
        $forecastDataAllDays = [];

        // Loop through each day of the week
        foreach ($daysOfWeek as $dayOfWeek => $dayName) {
            $adjustedColumnExpression = "calculations.$dayOfWeek + (calculations.$dayOfWeek * adjustment.$dayOfWeek / 100) AS adjusted_$dayOfWeek";

            $forecastData = Calculation::select(
                'calculations.forecast_id',
                'calculations.start_date AS date1',
                'calculations.end_date AS date2',
                DB::raw($adjustedColumnExpression),
                'params.avg_handling_time',
                'params.reporting_period',
                'params.service_level',
                'params.target_answer_time',
                'params.shrinkage'
            )
                ->leftJoin('params', 'params.id', '=', 'calculations.forecast_id')
                ->leftJoin('adjustment', 'adjustment.forecast_id', '=', 'calculations.forecast_id')
                ->where('calculations.forecast_id', $scheduling->forecast_id)
                ->first();

            $forecastProcedureName = "forecast.fte_req_$dayOfWeek";
            $adjustedValue = $forecastData["adjusted_$dayOfWeek"];

            // Call the stored procedure using the Laravel query builder
            try {
                $forecastedCallResults = DB::select(
                    "CALL $forecastProcedureName(?, ?, ?, ?, ?, ?, ?, ?)",
                    [
                        $adjustedValue,
                        $forecastData->avg_handling_time,
                        $forecastData->reporting_period,
                        $forecastData->service_level,
                        $forecastData->target_answer_time,
                        $forecastData->shrinkage,
                        $forecastData->date1,
                        $forecastData->date2
                    ]
                );
            } catch (\Exception $e) {
                // Handle the error here, e.g., log it, show a user-friendly message, etc.
                return back()->with('error', 'Error retrieving forecasted call demand.');
            }

            // Convert the result objects to associative arrays
            $forecastedCallArray = array_map(function ($result) {
                return (array) $result;
            }, $forecastedCallResults);

            // Extract the forecasted call demand for the specific day of the week
            $forecastedCallDemand = $forecastedCallArray[0];

            // Add the forecasted call demand to the array for all days
            $forecastDataAllDays[$dayName] = [
                'forecastedCallResults' => $forecastedCallResults,
                // ... (any other data you want to include)
            ];

        }

        // $getSchedules = DB::select('CALL forecast.generate_schedule(:schedule_id)', [
        //     'schedule_id' => $id,
        // ]);

        $getSchedules = ScheduleShift::select(
            'users.nik',
            'users.name',
            'users.gender',
            'users.religion',
            'master_shift.name AS shift',
            'schedule_shift.date',
            'schedule_shift.start_time',
            'schedule_shift.end_time',
            'schedule_shift.id AS schedule_shift',
            'schedule_period.id AS schedule_period',
            'schedule_period.publish',
        )
            ->leftJoin('users', 'users.id', '=', 'schedule_shift.agent_id')
            ->leftJoin('master_shift', 'master_shift.id', '=', 'schedule_shift.shift_id')
            ->leftJoin('schedule_period', 'schedule_period.id', '=', 'schedule_shift.schedule_id')
            ->groupBy(
                'users.nik',
                'users.name',
                'users.gender',
                'users.religion',
                'master_shift.name',
                'schedule_shift.date',
                'schedule_shift.start_time',
                'schedule_shift.end_time',
                'schedule_shift.id',
                'schedule_period.id',
                'schedule_period.publish',
            )
            ->where('schedule_period.id', $id)
            ->orderBy('schedule_shift.date')
            ->orderBy('schedule_shift.start_time')
            ->get();

        return view('schedule.show', compact('scheduling', 'shifts', 'availableAgents', 'forecastDataAllDays', 'daysOfWeek', 'getSchedules'));
    }

    public function generate($id)
    {
        $scheduling = SchedulePeriod::select(
            'schedule_period.id',
            'schedule_period.forecast_id',
            'master_skills.id AS skill_id',
            'master_skills.name AS skill',
            'params.start_date',
            'params.end_date',
            'params.reporting_period',
        )
            ->leftJoin('params', 'params.id', '=', 'schedule_period.forecast_id')
            ->leftJoin('master_skills', 'master_skills.id', '=', 'params.skill_id')
            ->whereNull('params.deleted_at')
            ->where('schedule_period.id', '=', $id)
            ->firstOrFail();

        $start_date = Carbon::parse($scheduling->start_date);
        $end_date = Carbon::parse($scheduling->end_date);

        // Generate an array of dates between the start and end dates
        $date_range = [];
        while ($start_date->lte($end_date)) {
            $date_range[] = $start_date->format('Y-m-d');
            $start_date->addDay();
        }

        $shifts = ShiftSkill::select(
            'master_shift.id',
            'master_shift.name',
            'master_shift.start_time',
            'master_shift.end_time'
        )
            ->where('skill_id', '=', $scheduling->skill_id)
            ->leftJoin('master_shift', 'master_shift.id', '=', 'shift_skills.shift_id')
            ->get();

        $availableAgents = User::select(
            'users.id',
            'users.nik',
            'users.name',
            'users.gender',
            'users.religion',
            'users.skill_id',
            'master_skills.name AS skill_name',
        )
            ->where('skill_id', '=', $scheduling->skill_id)
            ->leftJoin('master_skills', 'master_skills.id', '=', 'users.skill_id')
            ->get();

        $daysOfWeek = [
            'mon' => 'Monday',
            'tue' => 'Tuesday',
            'wed' => 'Wednesday',
            'thu' => 'Thursday',
            'fri' => 'Friday',
            'sat' => 'Saturday',
            'sun' => 'Sunday'
        ];

        foreach ($daysOfWeek as $dayOfWeek => $dayName) {
            $adjustedColumnExpression = "calculations.$dayOfWeek + (calculations.$dayOfWeek * adjustment.$dayOfWeek / 100) AS adjusted_$dayOfWeek";

            $forecastData = Calculation::select(
                'calculations.forecast_id',
                'calculations.start_date AS date1',
                'calculations.end_date AS date2',
                DB::raw($adjustedColumnExpression),
                'params.avg_handling_time',
                'params.reporting_period',
                'params.service_level',
                'params.target_answer_time',
                'params.shrinkage'
            )
                ->leftJoin('params', 'params.id', '=', 'calculations.forecast_id')
                ->leftJoin('adjustment', 'adjustment.forecast_id', '=', 'calculations.forecast_id')
                ->where('calculations.forecast_id', $scheduling->forecast_id)
                ->first();

            $forecastProcedureName = "forecast.fte_req_$dayOfWeek";
            $adjustedValue = $forecastData["adjusted_$dayOfWeek"];

            try {
                $forecastedCallResults = DB::select(
                    "CALL $forecastProcedureName(?, ?, ?, ?, ?, ?, ?, ?)",
                    [
                        $adjustedValue,
                        $forecastData->avg_handling_time,
                        $forecastData->reporting_period,
                        $forecastData->service_level,
                        $forecastData->target_answer_time,
                        $forecastData->shrinkage,
                        $forecastData->date1,
                        $forecastData->date2
                    ]
                );
            } catch (\Exception $e) {
                return back()->with('error', 'Error retrieving forecasted call demand.');
            }
        }

        foreach ($daysOfWeek as $dayOfWeek => $dayName) {
            $forecastedCallResults = $this->getForecastedCallResults($dayOfWeek, $forecastData);

            $date = $date_range[array_search($dayName, array_values($daysOfWeek))];

            $this->generateScheduleForDay(
                $id,
                $shifts,
                $availableAgents,
                $forecastedCallResults,
                $date
            );
        }

        return redirect()->route('schedule.show', $id)->with('status', 'Generated schedule successfully.');
    }

    private function getForecastedCallResults($dayOfWeek, $forecastData)
    {
        $adjustedColumnExpression = "calculations.$dayOfWeek + (calculations.$dayOfWeek * adjustment.$dayOfWeek / 100) AS adjusted_$dayOfWeek";

        $forecastedCallResults = Calculation::select(
            'calculations.forecast_id',
            'calculations.start_date AS date1',
            'calculations.end_date AS date2',
            DB::raw($adjustedColumnExpression),
            'params.avg_handling_time',
            'params.reporting_period',
            'params.service_level',
            'params.target_answer_time',
            'params.shrinkage'
        )
            ->leftJoin('params', 'params.id', '=', 'calculations.forecast_id')
            ->leftJoin('adjustment', 'adjustment.forecast_id', '=', 'calculations.forecast_id')
            ->where('calculations.forecast_id', $forecastData->forecast_id)
            ->first();

        $forecastProcedureName = "forecast.fte_req_$dayOfWeek";
        $adjustedValue = $forecastedCallResults["adjusted_$dayOfWeek"];

        try {
            $forecastedCallResults = DB::select(
                "CALL $forecastProcedureName(?, ?, ?, ?, ?, ?, ?, ?)",
                [
                    $adjustedValue,
                    $forecastedCallResults->avg_handling_time,
                    $forecastedCallResults->reporting_period,
                    $forecastedCallResults->service_level,
                    $forecastedCallResults->target_answer_time,
                    $forecastedCallResults->shrinkage,
                    $forecastedCallResults->date1,
                    $forecastedCallResults->date2
                ]
            );
        } catch (\Exception $e) {
            return back()->with('error', 'Error retrieving forecasted call demand.');
        }

        $forecastedCallArray = array_map(function ($result) {
            return (array) $result;
        }, $forecastedCallResults);

        $forecastedCallDemand = $forecastedCallArray[0];

        return $forecastedCallDemand;
    }

    private function generateScheduleForDay($id, $shifts, $availableAgents, $forecastedCallResults, $date)
    {
        foreach ($shifts as $shift) {
            $startHour = (int) substr($shift->start_time, 0, 2);
            $endHour = (int) substr($shift->end_time, 0, 2);

            $totalAgentsNeeded = 0;
            $assignedAgents = collect();

            for ($hour = $startHour; $hour < $endHour; $hour++) {
                $hourRange = sprintf("%02d", $hour) . ':00 - ' . sprintf("%02d", $hour + 1) . ':00';
                $totalAgentsNeeded += $forecastedCallResults[$hourRange];

                $availableAgentsForShift = $availableAgents->reject(function ($agent) use ($shift, $date) {
                    return $agent->hasAssignedShiftDuring($shift->start_time, $shift->end_time, $date) || $this->hasAssignedShiftOnSameDate($agent, $date);
                });

                $assignedAgentsForHour = $availableAgentsForShift->take(min($forecastedCallResults[$hourRange], count($availableAgentsForShift)));

                $assignedAgents = $assignedAgents->merge($assignedAgentsForHour);

                if ($totalAgentsNeeded >= $assignedAgents->count()) {
                    break;
                }
            }

            foreach ($availableAgents as $agent) {
                $user = User::where('name', $agent->name)->first();

                if ($agent->gender === 'Male' && $shift->start_time > '15:00:00' && !$agent->hasAssignedShiftDuring($shift->start_time, $shift->end_time, $date) && !$this->hasAssignedShiftOnSameDate($agent, $date)) {
                    // Assign male agents to afternoon shifts
                    ScheduleShift::create([
                        'schedule_id' => $id,
                        'agent_id' => $user->id,
                        'date' => $date,
                        'shift_id' => $shift->id,
                        'start_time' => $shift->start_time,
                        'end_time' => $shift->end_time,
                    ]);

                    $totalAgentsNeeded--;

                    if ($totalAgentsNeeded <= 0) {
                        break;
                    }
                } elseif ($agent->gender === 'Female' && $shift->start_time < '15:00:00' && !$agent->hasAssignedShiftDuring($shift->start_time, $shift->end_time, $date) && !$this->hasAssignedShiftOnSameDate($agent, $date)) {
                    // Assign female agents to morning shifts
                    ScheduleShift::create([
                        'schedule_id' => $id,
                        'agent_id' => $user->id,
                        'date' => $date,
                        'shift_id' => $shift->id,
                        'start_time' => $shift->start_time,
                        'end_time' => $shift->end_time,
                    ]);

                    $totalAgentsNeeded--;

                    if ($totalAgentsNeeded <= 0) {
                        break;
                    }
                }
            }
        }
    }

    private function hasAssignedShiftOnSameDate($agent, $date)
    {
        return $agent->shifts()->where('date', $date)->exists();
    }

    public function publish($id)
    {
        if (!Laratrust::isAbleTo('update-schedule')) {
            return abort(404);
        }

        $schedule = SchedulePeriod::findOrFail($id);
        $schedule->publish = 1;
        $schedule->save();

        return redirect()->route('schedule.show', $id)->with('status', 'Publish schedule successfully.');
    }

    public function unpublish($id)
    {
        if (!Laratrust::isAbleTo('update-schedule')) {
            return abort(404);
        }

        $schedule = SchedulePeriod::findOrFail($id);
        $schedule->publish = 0;
        $schedule->save();

        return redirect()->route('schedule.show', $id)->with('status', 'Unpublish schedule successfully.');
    }

    public function swap($id, Request $request)
    {
        if (!Laratrust::isAbleTo('update-schedule')) {
            return abort(404);
        }

        $shift = ShiftSkill::select(
            'master_shift.id',
            'master_shift.name',
            'master_shift.start_time',
            'master_shift.end_time'
        )
            ->where('master_shift.id', '=', $request->swap)
            ->leftJoin('master_shift', 'master_shift.id', '=', 'shift_skills.shift_id')
            ->first();

        $schedule = ScheduleShift::findOrFail($id);
        $schedule->shift_id = $shift->id;
        $schedule->start_time = $shift->start_time;
        $schedule->end_time = $shift->end_time;
        $schedule->save();

        return back()->with('status', 'Swap shift successfully.');
    }
}