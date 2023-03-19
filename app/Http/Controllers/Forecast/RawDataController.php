<?php

namespace App\Http\Controllers\Forecast;

use App\Http\Controllers\Controller;
use App\Imports\RawDataImport;
use App\Models\Forecast\RawData;
use DateTime;
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

        $rawData = RawData::orderBy('date', 'asc')->get();

        return DataTables::of($rawData)
            ->addColumn('day', function ($row) {
                $dateTime = new DateTime($row->date);
                return $dateTime->format('D');
            })
            ->addColumn('date', function ($row) {
                $dateTime = new DateTime($row->date);
                return $dateTime->format('d M Y');
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

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
