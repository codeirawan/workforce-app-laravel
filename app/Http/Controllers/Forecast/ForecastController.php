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
        $skills = Skill::select('id', 'name')->orderBy('name')->get();

        return view('forecast.index', compact('cities', 'projects', 'skills'));
    }

    public function store(Request $request)
    {
        if (!Laratrust::isAbleTo('create-forecast')) {
            return abort(404);
        }

        $this->validate($request, [
            'city_id' => 'required',
            'project_id' => 'required',
            'skill_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'avg_handling_time' => 'required|integer',
            'reporting_period' => 'required|integer',
            'service_level' => 'required|integer',
            'target_answer_time' => 'required|integer',
            'shrinkage' => 'required|integer',
        ]);

        DB::beginTransaction();
        try {
            $forecast = new Params;
            $forecast->city_id = $request->city_id;
            $forecast->project_id = $request->project_id;
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

        return view('forecast.show', compact('params'));
    }

    public function paramsForecast()
    {
        if (!Laratrust::isAbleTo('view-forecast')) {
            return abort(404);
        }

        $paramsForecast = DB::select('CALL forecast.get_forecast_params()');

        return DataTables::of($paramsForecast)
            ->addColumn('action', function ($row) {
                $view = '<a href="' . route('forecast.show', $row->id) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Calculation') . '"><i class="la la-wrench"></i></a>';
                // $delete = '<a href="#" data-href="' . route('forecast.destroy', $row->id) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Delete') . '" data-toggle="modal" data-target="#modal-delete" data-key="' . $row->start_date . '"><i class="la la-trash"></i></a>';

                return (Laratrust::isAbleTo('view-forecast') ? $view : '');
                //  . (Laratrust::isAbleTo('delete-forecast') ? $delete : '');
            })
            ->rawColumns(['action'])
            ->make(true);

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

    public function resultForecast($id)
    {
        if (!Laratrust::isAbleTo('view-forecast')) {
            return abort(404);
        }

        $getForecastId = Calculation::select('forecast_id')->where('forecast_id', $id)->get();

        if (!$getForecastId->isEmpty()) {
            $forecastId = $getForecastId->first()->forecast_id;

            $resultForecast = DB::select('CALL forecast.sum_forecast(?)', [$forecastId]);

            return DataTables::of($resultForecast)->make(true);

        } else {
            return response()->json(['data' => []]);
        }
    }

}
