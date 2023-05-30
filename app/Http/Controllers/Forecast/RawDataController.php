<?php

namespace App\Http\Controllers\Forecast;

use App\Http\Controllers\Controller;
use App\Imports\RawDataImport;
use App\Models\Forecast\RawData;
use App\Models\Master\City;
use App\Models\Master\Project;
use App\Models\Master\Skill;
use DateTime;
use DB;
use Illuminate\Http\Request;
use Lang;
use Laratrust;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class RawDataController extends Controller
{
    public function index()
    {
        if (!Laratrust::isAbleTo('view-forecast')) {
            return abort(404);
        }

        return view('forecast.raw-data.index');
    }
    public function data()
    {
        if (!Laratrust::isAbleTo('view-forecast')) {
            return abort(404);
        }

        $rawData = DB::select('CALL forecast.raw_data()');

        return DataTables::of($rawData)
            ->addColumn('date', function ($row) {
                $dateTime = new DateTime($row->date);
                return $dateTime->format('D, d M Y');
            })
            ->addColumn('start_time', function ($row) {
                $startTime = new DateTime($row->start_time);
                return $startTime->format('H:i:s');
            })
            ->addColumn('end_time', function ($row) {
                $startTime = new DateTime($row->end_time);
                return $startTime->format('H:i:s');
            })
            ->addColumn('action', function ($row) {
                $edit = '<a href="' . route('raw-data.edit', $row->id) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Edit') . '"><i class="la la-edit"></i></a>';
                $delete = '<a href="#" data-href="' . route('raw-data.destroy', $row->id) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Delete') . '" data-toggle="modal" data-target="#modal-delete" data-key="' . $row->date . '"><i class="la la-trash"></i></a>';

                return (Laratrust::isAbleTo('update-forecast') ? $edit : '') . (Laratrust::isAbleTo('delete-forecast') ? $delete : '');
            })
            ->rawColumns(['action'])
            ->make(true);

    }

    public function bulk(Request $request)
    {
        $request->validate([
            'raw-data' => 'required|mimes:xls,xlsx',
        ]);

        if ($request->hasFile('raw-data')) {

            Excel::import(new RawDataImport, request()->file('raw-data'));
        }
        return back()->with('success', 'Bulk raw data upload was successfully!');

    }

    public function edit($id)
    {
        if (!Laratrust::isAbleTo('update-forecast')) {
            return abort(404);
        }

        $rawData = DB::select('CALL forecast.raw_data_by_id(?)', [$id]);
        $rawData = $rawData[0];

        $cities = City::select('id', 'name')->orderBy('name')->whereIn('id', ['3171', '3374', '3471', '3372'])->get();
        $projects = Project::select('id', 'name')->orderBy('name')->get();
        $skills = Skill::select('id', 'name')->orderBy('name')->get();

        return view('forecast.raw-data.edit', compact('rawData', 'cities', 'projects', 'skills'));
    }

    public function update($id, Request $request)
    {
        if (!Laratrust::isAbleTo('update-forecast')) {
            return abort(404);
        }

        $rawData = RawData::findOrFail($id);

        $this->validate($request, [
            'date' => ['required', 'date_format:Y-m-d'],
            'start_time' => ['required', 'date_format:Y-m-d H:i:s'],
            'end_time' => ['required', 'date_format:Y-m-d H:i:s'],
            'volume' => ['required', 'integer'],
            'city_id' => ['required', 'integer', 'exists:master_cities,id'],
            'project_id' => ['required', 'integer', 'exists:master_projects,id'],
            'skill_id' => ['required', 'integer', 'exists:master_skills,id'],
        ]);

        $rawData->date = $request->date;
        $rawData->start_time = $request->start_time;
        $rawData->end_time = $request->end_time;
        $rawData->volume = $request->volume;
        $rawData->city_id = $request->city_id;
        $rawData->project_id = $request->project_id;
        $rawData->skill_id = $request->skill_id;
        $rawData->save();

        $message = Lang::get('Raw Data interval ') . $rawData->start_time . ('-') . '\' ' . $rawData->end_time . '\' ' . Lang::get('successfully updated.');
        return redirect()->route('raw-data.index')->with('status', $message);
    }

    public function destroy($id)
    {
        if (!Laratrust::isAbleTo('delete-forecast')) {
            return abort(404);
        }

        $rawData = RawData::findOrFail($id);
        $start = $rawData->start_time;
        $end = $rawData->end_time;
        $rawData->delete();

        $message = Lang::get('Raw Data interval ') . ' \'' . $start . '\' ' . ('-') . ' \'' . $end . '\' ' . Lang::get('was deleted.');
        return redirect()->route('raw-data.index')->with('status', $message);
    }
}
