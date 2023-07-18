<?php

namespace App\Http\Controllers\Forecast;

use App\Http\Controllers\Controller;
use App\Models\Forecast\Calculation;
use App\Models\Forecast\Params;
use App\Models\Master\City;
use App\Models\Master\Project;
use App\Models\Master\Skill;
use DB;
use Illuminate\Http\Request;
use Lang;
use Laratrust;
use Yajra\DataTables\Facades\DataTables;

class ForecastController extends Controller
{
    public function index()
    {
        if (!Laratrust::isAbleTo('view-forecast')) {
            return abort(404);
        }

        $cities = City::select('id', 'name')->orderBy('name')->whereIn('id', ['3171', '3374', '3471', '3372'])->get();
        $projects = Project::select('id', 'name')->orderBy('name')->get();
        $skills = Skill::select(
            'master_cities.name AS site',
            'master_projects.name AS project',
            'master_skills.name AS skill',
            'master_skills.id'
        )
            ->leftJoin('master_cities', 'master_cities.id', '=', 'master_skills.city_id')
            ->leftJoin('master_projects', 'master_projects.id', '=', 'master_skills.project_id')->get();

        return view('forecast.index', compact('cities', 'projects', 'skills'));
    }

    public function store(Request $request)
    {
        if (!Laratrust::isAbleTo('create-forecast')) {
            return abort(404);
        }

        $this->validate($request, [
            // 'city_id' => 'required',
            // 'project_id' => 'required',
            'skill_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'avg_handling_time' => 'required|integer',
            'reporting_period' => 'required|integer',
            'service_level' => 'required|integer',
            'target_answer_time' => 'required|integer',
            'shrinkage' => 'required|integer',
        ]);

        $skill = Skill::select('city_id', 'project_id')->findOrFail($request->skill_id);

        DB::beginTransaction();
        try {
            $forecast = new Params;
            $forecast->city_id = $skill->city_id;
            $forecast->project_id = $skill->project_id;
            $forecast->skill_id = $request->skill_id;
            $forecast->start_date = $request->start_date;
            $forecast->end_date = $request->end_date;
            $forecast->avg_handling_time = $request->avg_handling_time;
            $forecast->reporting_period = $request->reporting_period;
            $forecast->service_level = $request->service_level;
            $forecast->target_answer_time = $request->target_answer_time;
            $forecast->shrinkage = $request->shrinkage;
            $forecast->save();

        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return abort(500);
        }
        DB::commit();

        $message = Lang::get('Forecast period') . ' \'' . $forecast->start_date . '\' ' . '-' . ' \'' . $forecast->end_date . '\' ' . Lang::get('successfully created.');
        return redirect()->route('forecast.index')->with('status', $message);
    }

    public function show($id)
    {
        if (!Laratrust::isAbleTo('view-forecast')) {
            return abort(404);
        }

        $params = Params::all()->where('id', '=', $id)->firstOrFail();
        $skill = Skill::select(
            'master_cities.name AS site',
            'master_projects.name AS project',
            'master_skills.name AS skill',
            'master_skills.id'
        )
            ->leftJoin('master_cities', 'master_cities.id', '=', 'master_skills.city_id')
            ->leftJoin('master_projects', 'master_projects.id', '=', 'master_skills.project_id')
            ->whereCityId($params->city_id)
            ->whereProjectId($params->project_id)
            ->where('master_skills.id', $params->skill_id)
            ->firstOrFail();

        return view('forecast.show', compact('params', 'skill'));
    }

    public function paramsForecast()
    {
        if (!Laratrust::isAbleTo('view-forecast')) {
            return abort(404);
        }

        $paramsForecast = DB::select('CALL forecast.get_forecast_params()');

        return DataTables::of($paramsForecast)
            ->addColumn('action', function ($row) {
                $view = '<a href="' . route('forecast.show', $row->id) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('FTE Requirement') . '"><i class="fa-solid fa-sm fa-list-ol"></i></a>';
                $edit = '<a href="' . route('forecast.edit', $row->id) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Edit') . '"><i class="fa-solid fa-sm fa-edit"></i></a>';
                $delete = '<a href="#" data-href="' . route('forecast.destroy', $row->id) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Delete') . '" data-toggle="modal" data-target="#modal-delete" data-key="' . $row->skill . ' - ' . $row->project . ' ' . $row->site . ' | Date ' . $row->start_date . ' ~ ' . $row->end_date . '"><i class="fa-solid fa-sm fa-trash"></i></a>';

                return (Laratrust::isAbleTo('view-forecast') ? $view : '')
                    . (Laratrust::isAbleTo('update-forecast') ? $edit : '')
                    . (Laratrust::isAbleTo('delete-forecast') ? $delete : '');
            })
            ->rawColumns(['action'])
            ->make(true);

    }

    public function edit($id)
    {
        if (!Laratrust::isAbleTo('update-forecast')) {
            return abort(404);
        }

        $params = Params::all()->where('id', '=', $id)->firstOrFail();
        $skill = Skill::select(
            'master_cities.name AS site',
            'master_projects.name AS project',
            'master_skills.name AS skill',
            'master_skills.id'
        )
            ->leftJoin('master_cities', 'master_cities.id', '=', 'master_skills.city_id')
            ->leftJoin('master_projects', 'master_projects.id', '=', 'master_skills.project_id')
            ->whereCityId($params->city_id)
            ->whereProjectId($params->project_id)
            ->where('master_skills.id', $params->skill_id)
            ->firstOrFail();

        return view('forecast.edit', compact('params', 'skill'));
    }

    public function update($id, Request $request)
    {
        if (!Laratrust::isAbleTo('update-forecast')) {
            return abort(404);
        }

        $this->validate($request, [
            'start_date' => 'required',
            'end_date' => 'required',
            'avg_handling_time' => 'required|integer',
            'reporting_period' => 'required|integer',
            'service_level' => 'required|integer',
            'target_answer_time' => 'required|integer',
            'shrinkage' => 'required|integer',
        ]);

        $forecast = Params::findOrFail($id);

        DB::beginTransaction();
        try {
            $forecast->start_date = $request->start_date;
            $forecast->end_date = $request->end_date;
            $forecast->avg_handling_time = $request->avg_handling_time;
            $forecast->reporting_period = $request->reporting_period;
            $forecast->service_level = $request->service_level;
            $forecast->target_answer_time = $request->target_answer_time;
            $forecast->shrinkage = $request->shrinkage;
            $forecast->save();

        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return abort(500);
        }
        DB::commit();

        $message = Lang::get('Forecast period') . ' \'' . $forecast->start_date . '\' ' . '-' . ' \'' . $forecast->end_date . '\' ' . Lang::get('successfully updated.');
        return redirect()->route('forecast.index')->with('status', $message);
    }

    public function destroy($id)
    {
        if (!Laratrust::isAbleTo('delete-forecast')) {
            return abort(404);
        }

        $forecast = Params::findOrFail($id);
        $start = $forecast->start_date;
        $end = $forecast->end_date;
        $forecast->delete();

        $message = Lang::get('Forecast period ') . ' \'' . $start . '\' ' . ('-') . ' \'' . $end . '\' ' . Lang::get('was deleted.');
        return redirect()->route('forecast.index')->with('status', $message);
    }

    public function showHistory($id)
    {
        if (!Laratrust::isAbleTo('view-forecast')) {
            return abort(404);
        }

        $getHistory = Calculation::where('forecast_id', $id)->get();

        return DataTables::of($getHistory)
            ->addColumn('action', function ($row) {
                $delete = '<a href="#" data-href="' . route('forecast.destroy', $row->id) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Delete') . '" data-toggle="modal" data-target="#modal-delete" data-key="' . $row->title . '"><i class="la la-trash"></i></a>';

                return (Laratrust::isAbleTo('delete-forecast') ? $delete : '');
            })
            ->rawColumns(['action'])
            ->make(true);

    }

    public function dataHistory($id)
    {
        if (!Laratrust::isAbleTo('view-forecast')) {
            return abort(404);
        }

        $getHistory = Params::select('city_id', 'project_id', 'skill_id')->where('id', $id)->get();

        // Assuming you want to retrieve the filter parameters from the first record
        $cityId = $getHistory->first()->city_id;
        $projectId = $getHistory->first()->project_id;
        $skillId = $getHistory->first()->skill_id;

        $history = DB::select('CALL forecast.sum_history_per_week_filter(:city_id, :project_id, :skill_id)', [
            'city_id' => $cityId,
            'project_id' => $projectId,
            'skill_id' => $skillId,
        ]);

        return $history;
    }

    public function addCalculation(Request $request)
    {
        if (!Laratrust::isAbleTo('create-forecast')) {
            return abort(404);
        }

        $selectedData = $request->input('selectedData');

        // Insert the selected data into another table
        foreach ($selectedData as $data) {
            Calculation::create([
                'forecast_id' => $data['forecast_id'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'mon' => intval($data['mon']),
                'tue' => intval($data['tue']),
                'wed' => intval($data['wed']),
                'thu' => intval($data['thu']),
                'fri' => intval($data['fri']),
                'sat' => intval($data['sat']),
                'sun' => intval($data['sun']),
                'sum' => intval($data['sum']),
                'avg' => floatval($data['avg']),
            ]);
        }

        return response()->json(['success' => true]);

    }

    public function averageHistory($id)
    {
        if (!Laratrust::isAbleTo('view-forecast')) {
            return abort(404);
        }

        $getForecastId = Calculation::select('forecast_id')->where('forecast_id', $id)->get();

        if (!$getForecastId->isEmpty()) {
            $forecastId = $getForecastId->first()->forecast_id;

            $averageHistory = DB::select('CALL forecast.avg_history_by_forecast_id(?)', [$forecastId]);

            return DataTables::of($averageHistory)->make(true);

        } else {
            return response()->json(['data' => []]);

        }
    }

    public function fteReqMon($id)
    {
        if (!Laratrust::isAbleTo('view-forecast')) {
            return abort(404);
        }

        $getParams = Calculation::select(
            'calculations.forecast_id',
            'calculations.start_date AS date1',
            'calculations.end_date AS date2',
            'calculations.mon',
            'params.avg_handling_time',
            'params.reporting_period',
            'params.service_level',
            'params.service_level',
            'params.target_answer_time',
            'params.shrinkage',
        )
            ->leftJoin('params', 'params.id', '=', 'calculations.forecast_id')
            ->where('forecast_id', $id)
            ->first();

        if ($getParams !== null) {
            $intervalMonday = DB::select(
                'CALL forecast.fte_req_mon(:mon, :avg_handling_time, :reporting_period, :service_level, :target_answer_time, :shrinkage, :date1, :date2)',
                [
                    'mon' => $getParams->mon,
                    'avg_handling_time' => $getParams->avg_handling_time,
                    'reporting_period' => $getParams->reporting_period,
                    'service_level' => $getParams->service_level,
                    'target_answer_time' => $getParams->target_answer_time,
                    'shrinkage' => $getParams->shrinkage,
                    'date1' => $getParams->date1,
                    'date2' => $getParams->date2
                ]
            );

            return DataTables::of($intervalMonday)->make(true);
        } else {
            return response()->json(['data' => []]);
        }
    }

    public function fteReqTue($id)
    {
        if (!Laratrust::isAbleTo('view-forecast')) {
            return abort(404);
        }

        $getParams = Calculation::select(
            'calculations.forecast_id',
            'calculations.start_date AS date1',
            'calculations.end_date AS date2',
            'calculations.tue',
            'params.avg_handling_time',
            'params.reporting_period',
            'params.service_level',
            'params.service_level',
            'params.target_answer_time',
            'params.shrinkage',
        )
            ->leftJoin('params', 'params.id', '=', 'calculations.forecast_id')
            ->where('forecast_id', $id)
            ->first();

        if ($getParams !== null) {
            $intervalTuesday = DB::select(
                'CALL forecast.fte_req_tue(:tue, :avg_handling_time, :reporting_period, :service_level, :target_answer_time, :shrinkage, :date1, :date2)',
                [
                    'tue' => $getParams->tue,
                    'avg_handling_time' => $getParams->avg_handling_time,
                    'reporting_period' => $getParams->reporting_period,
                    'service_level' => $getParams->service_level,
                    'target_answer_time' => $getParams->target_answer_time,
                    'shrinkage' => $getParams->shrinkage,
                    'date1' => $getParams->date1,
                    'date2' => $getParams->date2
                ]
            );

            return DataTables::of($intervalTuesday)->make(true);
        } else {
            return response()->json(['data' => []]);
        }
    }

    public function fteReqWed($id)
    {
        if (!Laratrust::isAbleTo('view-forecast')) {
            return abort(404);
        }

        $getParams = Calculation::select(
            'calculations.forecast_id',
            'calculations.start_date AS date1',
            'calculations.end_date AS date2',
            'calculations.wed',
            'params.avg_handling_time',
            'params.reporting_period',
            'params.service_level',
            'params.service_level',
            'params.target_answer_time',
            'params.shrinkage',
        )
            ->leftJoin('params', 'params.id', '=', 'calculations.forecast_id')
            ->where('forecast_id', $id)
            ->first();

        if ($getParams !== null) {
            $intervalWednesday = DB::select(
                'CALL forecast.fte_req_wed(:wed, :avg_handling_time, :reporting_period, :service_level, :target_answer_time, :shrinkage, :date1, :date2)',
                [
                    'wed' => $getParams->wed,
                    'avg_handling_time' => $getParams->avg_handling_time,
                    'reporting_period' => $getParams->reporting_period,
                    'service_level' => $getParams->service_level,
                    'target_answer_time' => $getParams->target_answer_time,
                    'shrinkage' => $getParams->shrinkage,
                    'date1' => $getParams->date1,
                    'date2' => $getParams->date2
                ]
            );

            return DataTables::of($intervalWednesday)->make(true);
        } else {
            return response()->json(['data' => []]);
        }
    }

    public function fteReqThu($id)
    {
        if (!Laratrust::isAbleTo('view-forecast')) {
            return abort(404);
        }

        $getParams = Calculation::select(
            'calculations.forecast_id',
            'calculations.start_date AS date1',
            'calculations.end_date AS date2',
            'calculations.thu',
            'params.avg_handling_time',
            'params.reporting_period',
            'params.service_level',
            'params.service_level',
            'params.target_answer_time',
            'params.shrinkage',
        )
            ->leftJoin('params', 'params.id', '=', 'calculations.forecast_id')
            ->where('forecast_id', $id)
            ->first();

        if ($getParams !== null) {
            $intervalThursday = DB::select(
                'CALL forecast.fte_req_thu(:thu, :avg_handling_time, :reporting_period, :service_level, :target_answer_time, :shrinkage, :date1, :date2)',
                [
                    'thu' => $getParams->thu,
                    'avg_handling_time' => $getParams->avg_handling_time,
                    'reporting_period' => $getParams->reporting_period,
                    'service_level' => $getParams->service_level,
                    'target_answer_time' => $getParams->target_answer_time,
                    'shrinkage' => $getParams->shrinkage,
                    'date1' => $getParams->date1,
                    'date2' => $getParams->date2
                ]
            );

            return DataTables::of($intervalThursday)->make(true);
        } else {
            return response()->json(['data' => []]);
        }
    }

    public function fteReqFri($id)
    {
        if (!Laratrust::isAbleTo('view-forecast')) {
            return abort(404);
        }

        $getParams = Calculation::select(
            'calculations.forecast_id',
            'calculations.start_date AS date1',
            'calculations.end_date AS date2',
            'calculations.fri',
            'params.avg_handling_time',
            'params.reporting_period',
            'params.service_level',
            'params.service_level',
            'params.target_answer_time',
            'params.shrinkage',
        )
            ->leftJoin('params', 'params.id', '=', 'calculations.forecast_id')
            ->where('forecast_id', $id)
            ->first();

        if ($getParams !== null) {
            $intervalFriday = DB::select(
                'CALL forecast.fte_req_fri(:fri, :avg_handling_time, :reporting_period, :service_level, :target_answer_time, :shrinkage, :date1, :date2)',
                [
                    'fri' => $getParams->fri,
                    'avg_handling_time' => $getParams->avg_handling_time,
                    'reporting_period' => $getParams->reporting_period,
                    'service_level' => $getParams->service_level,
                    'target_answer_time' => $getParams->target_answer_time,
                    'shrinkage' => $getParams->shrinkage,
                    'date1' => $getParams->date1,
                    'date2' => $getParams->date2
                ]
            );

            return DataTables::of($intervalFriday)->make(true);
        } else {
            return response()->json(['data' => []]);
        }
    }

    public function fteReqSat($id)
    {
        if (!Laratrust::isAbleTo('view-forecast')) {
            return abort(404);
        }

        $getParams = Calculation::select(
            'calculations.forecast_id',
            'calculations.start_date AS date1',
            'calculations.end_date AS date2',
            'calculations.sat',
            'params.avg_handling_time',
            'params.reporting_period',
            'params.service_level',
            'params.service_level',
            'params.target_answer_time',
            'params.shrinkage',
        )
            ->leftJoin('params', 'params.id', '=', 'calculations.forecast_id')
            ->where('forecast_id', $id)
            ->first();

        if ($getParams !== null) {
            $intervalSaturday = DB::select(
                'CALL forecast.fte_req_sat(:sat, :avg_handling_time, :reporting_period, :service_level, :target_answer_time, :shrinkage, :date1, :date2)',
                [
                    'sat' => $getParams->sat,
                    'avg_handling_time' => $getParams->avg_handling_time,
                    'reporting_period' => $getParams->reporting_period,
                    'service_level' => $getParams->service_level,
                    'target_answer_time' => $getParams->target_answer_time,
                    'shrinkage' => $getParams->shrinkage,
                    'date1' => $getParams->date1,
                    'date2' => $getParams->date2
                ]
            );

            return DataTables::of($intervalSaturday)->make(true);
        } else {
            return response()->json(['data' => []]);
        }
    }

    public function fteReqSun($id)
    {
        if (!Laratrust::isAbleTo('view-forecast')) {
            return abort(404);
        }

        $getParams = Calculation::select(
            'calculations.forecast_id',
            'calculations.start_date AS date1',
            'calculations.end_date AS date2',
            'calculations.sun',
            'params.avg_handling_time',
            'params.reporting_period',
            'params.service_level',
            'params.service_level',
            'params.target_answer_time',
            'params.shrinkage',
        )
            ->leftJoin('params', 'params.id', '=', 'calculations.forecast_id')
            ->where('forecast_id', $id)
            ->first();

        if ($getParams !== null) {
            $intervalSunday = DB::select(
                'CALL forecast.fte_req_sun(:sun, :avg_handling_time, :reporting_period, :service_level, :target_answer_time, :shrinkage, :date1, :date2)',
                [
                    'sun' => $getParams->sun,
                    'avg_handling_time' => $getParams->avg_handling_time,
                    'reporting_period' => $getParams->reporting_period,
                    'service_level' => $getParams->service_level,
                    'target_answer_time' => $getParams->target_answer_time,
                    'shrinkage' => $getParams->shrinkage,
                    'date1' => $getParams->date1,
                    'date2' => $getParams->date2
                ]
            );

            return DataTables::of($intervalSunday)->make(true);
        } else {
            return response()->json(['data' => []]);
        }
    }

}