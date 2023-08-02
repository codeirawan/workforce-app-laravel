<?php

namespace App\Http\Controllers\Forecast;

use App\Http\Controllers\Controller;
use App\Models\Forecast\Adjust;
use App\Models\Forecast\Calculation;
use App\Models\Forecast\Params;
use App\Models\Master\City;
use App\Models\Master\Project;
use App\Models\Master\Skill;
use Carbon\Carbon;
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

    public function params()
    {
        if (!Laratrust::isAbleTo('view-forecast')) {
            return abort(404);
        }

        $paramsForecast = Params::select(
            'params.id',
            'params.start_date',
            'params.end_date',
            'master_cities.name AS site',
            'master_projects.name AS project',
            'master_skills.name AS skill',
            'params.reporting_period',
            'params.target_answer_time',
            'params.service_level',
            'params.avg_handling_time',
            'params.shrinkage'
        )
            ->leftJoin('master_cities', 'master_cities.id', '=', 'params.city_id')
            ->leftJoin('master_projects', 'master_projects.id', '=', 'params.project_id')
            ->leftJoin('master_skills', 'master_skills.id', '=', 'params.skill_id')
            ->whereNull('params.deleted_at')
            ->get();


        return DataTables::of($paramsForecast)
            ->addColumn('action', function ($row) {
                $startDate = Carbon::parse($row->start_date)->format('d M Y');
                $endDate = Carbon::parse($row->end_date)->format('d M Y');

                $view = '<a href="' . route('forecast.show', $row->id) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('View') . '"><i class="fa-solid fa-sm fa-eye"></i></a>';
                $edit = '<a href="' . route('forecast.edit', $row->id) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Edit') . '"><i class="fa-solid fa-sm fa-edit"></i></a>';
                $delete = '<a href="#" data-href="' . route('forecast.destroy', $row->id) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Delete') . '" data-toggle="modal" data-target="#modal-delete" data-key="' . $row->skill . ' - ' . $row->project . ' ' . $row->site . ' | Period ' . $startDate . ' ~ ' . $endDate . '"><i class="fa-solid fa-sm fa-trash"></i></a>';

                return (Laratrust::isAbleTo('view-forecast') ? $view : '')
                    . (Laratrust::isAbleTo('update-forecast') ? $edit : '')
                    . (Laratrust::isAbleTo('delete-forecast') ? $delete : '');
            })
            ->rawColumns(['action'])
            ->make(true);

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

            $adjust = new Adjust;
            $adjust->forecast_id = $forecast->id;
            $adjust->save();

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

        $adjust = Adjust::select('id', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun')->where('forecast_id', '=', $id)->firstOrFail();

        return view('forecast.show', compact('params', 'skill', 'adjust'));
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
        $start = Carbon::parse($forecast->start_date)->format('d M Y');
        $end = Carbon::parse($forecast->end_date)->format('d M Y');
        $forecast->delete();

        $message = Lang::get('Forecast period ') . ' \'' . $start . '\' ' . ('-') . ' \'' . $end . '\' ' . Lang::get('was deleted.');
        return back()->with('status', $message);
    }

    public function showHistory($id)
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

    public function dataHistory($id)
    {
        if (!Laratrust::isAbleTo('view-forecast')) {
            return abort(404);
        }

        $getHistory = Calculation::where('forecast_id', $id)->get();

        return DataTables::of($getHistory)
            ->addColumn('action', function ($row) {
                $startDate = Carbon::parse($row->start_date)->format('d M Y');
                $endDate = Carbon::parse($row->end_date)->format('d M Y');

                $delete = '<a href="#" data-href="' . route('forecast.history.destroy', $row->id) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Delete') . '" data-toggle="modal" data-target="#modal-delete" data-key="' . ' Period ' . $startDate . ' ~ ' . $endDate . '"><i class="fa-solid fa-sm fa-trash"></i></a>';

                return (Laratrust::isAbleTo('delete-forecast') ? $delete : '');
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function storeHistory(Request $request)
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
    public function destroyHistory($id)
    {
        if (!Laratrust::isAbleTo('delete-forecast')) {
            return abort(404);
        }

        $history = Calculation::findOrFail($id);
        $start = Carbon::parse($history->start_date)->format('d M Y');
        $end = Carbon::parse($history->end_date)->format('d M Y');
        $history->delete();

        $message = Lang::get('History weekly period ') . ' \'' . $start . '\' ' . ('-') . ' \'' . $end . '\' ' . Lang::get('was deleted.');
        return back()->with('status', $message);
    }

    public function dataAdjust($id)
    {
        if (!Laratrust::isAbleTo('view-forecast')) {
            return abort(404);
        }

        $getForecastId = Calculation::select('forecast_id')->where('forecast_id', $id)->get();

        if (!$getForecastId->isEmpty()) {
            $forecastId = $getForecastId->first()->forecast_id;

            $getAdjust = Adjust::select('id', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun')->where('forecast_id', $forecastId)->get();

            return DataTables::of($getAdjust)
                ->addColumn('action', function ($row) {
                    $edit = '<a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Edit') . '" data-toggle="modal" data-target="#modal-edit-adjust" data-key="' . $row->id . '"><i class="fa-solid fa-sm fa-edit"></i></a>';

                    return (Laratrust::isAbleTo('update-forecast') ? $edit : '');
                })
                ->rawColumns(['action'])
                ->make(true);

        } else {
            return response()->json(['data' => []]);

        }
    }

    public function updateAdjust($id, Request $request)
    {
        if (!Laratrust::isAbleTo('update-forecast')) {
            return abort(404);
        }

        $request->validate([
            'mon' => 'required|integer',
            'tue' => 'required|integer',
            'wed' => 'required|integer',
            'thu' => 'required|integer',
            'fri' => 'required|integer',
            'sat' => 'required|integer',
            'sun' => 'required|integer',
        ]);

        DB::beginTransaction();
        try {
            $adjust = Adjust::where('forecast_id', $id)->firstOrFail();
            $adjust->update($request->only(['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun']));
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return abort(500);
        }
        DB::commit();

        $message = Lang::get('Adjustment forecast successfully updated.');
        return back()->with('status', $message);
    }

    public function fteReqDay($day, $id)
    {
        if (!Laratrust::isAbleTo('view-forecast')) {
            return abort(404);
        }

        $adjustedColumn = "calculations.$day + (calculations.$day * adjustment.$day / 100) AS adjusted_$day";

        $getParams = Calculation::select(
            'calculations.forecast_id',
            'calculations.start_date AS date1',
            'calculations.end_date AS date2',
            DB::raw($adjustedColumn),
            'params.avg_handling_time',
            'params.reporting_period',
            'params.service_level',
            'params.target_answer_time',
            'params.shrinkage'
        )
            ->leftJoin('params', 'params.id', '=', 'calculations.forecast_id')
            ->leftJoin('adjustment', 'adjustment.forecast_id', '=', 'calculations.forecast_id')
            ->where('calculations.forecast_id', $id)
            ->first();

        if ($getParams !== null) {
            $procedureName = "forecast.fte_req_$day";
            $adjustedValue = $getParams["adjusted_$day"];

            // Call the stored procedure using the Laravel query builder
            $intervalData = DB::select(
                "CALL $procedureName(?, ?, ?, ?, ?, ?, ?, ?)",
                [
                    $adjustedValue,
                    $getParams->avg_handling_time,
                    $getParams->reporting_period,
                    $getParams->service_level,
                    $getParams->target_answer_time,
                    $getParams->shrinkage,
                    $getParams->date1,
                    $getParams->date2
                ]
            );

            return DataTables::of($intervalData)->make(true);
        } else {
            return response()->json(['data' => []]);
        }
    }

}