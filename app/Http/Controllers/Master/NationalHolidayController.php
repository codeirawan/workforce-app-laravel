<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\NationalHoliday;
use DataTables;
use Illuminate\Http\Request;
use Lang;
use Laratrust;

class NationalHolidayController extends Controller
{
    public function index()
    {
        if (!Laratrust::isAbleTo('view-master')) {
            return abort(404);
        }

        return view('master.national-holiday.index');
    }

    public function data()
    {
        if (!Laratrust::isAbleTo('view-master')) {
            return abort(404);
        }

        $nationalHolidays = NationalHoliday::select('id', 'name', 'date', 'religion');

        return DataTables::of($nationalHolidays)
            ->addColumn('action', function ($nationalHoliday) {
                $edit = '<a href="' . route('master.national-holiday.edit', $nationalHoliday->id) . '"
                class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Edit') . '"><i
                class="la la-edit"></i></a>';
                $delete = '<a href="#" data-href="' . route('master.national-holiday.destroy', $nationalHoliday->id) . '"
                class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Delete') . '"
                data-toggle="modal" data-target="#modal-delete" data-key="' . $nationalHoliday->name . '"><i class="la la-trash"></i></a>';

                return (Laratrust::isAbleTo('update-master') ? $edit : '') . (Laratrust::isAbleTo('delete-master') ? $delete : '');
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        if (!Laratrust::isAbleTo('create-master')) {
            return abort(404);
        }

        return view('master.national-holiday.create');
    }

    public function store(Request $request)
    {
        if (!Laratrust::isAbleTo('create-master')) {
            return abort(404);
        }

        $this->validate($request, [
            'name' => ['required', 'string', 'max:191'],
            'date' => ['required'],
            'religion' => ['required', 'in:Muslim,Christian,Catholic,Hinduism,Buddhism,Confucianism,-'],
        ]);

        $nationalHoliday = new NationalHoliday;
        $nationalHoliday->name = $request->name;
        $nationalHoliday->date = $request->date;
        $nationalHoliday->religion = $request->religion;
        $nationalHoliday->save();

        $message = Lang::get('National Holiday') . ' \'' . $nationalHoliday->name . '\' ' . Lang::get('successfully created.');
        return redirect()->route('master.national-holiday.index')->with('status', $message);
    }

    public function edit($id)
    {
        if (!Laratrust::isAbleTo('update-master')) {
            return abort(404);
        }

        $nationalHoliday = NationalHoliday::select('id', 'name', 'date', 'religion')->findOrFail($id);

        return view('master.national-holiday.edit', compact('nationalHoliday'));
    }

    public function update($id, Request $request)
    {
        if (!Laratrust::isAbleTo('update-master')) {
            return abort(404);
        }

        $nationalHoliday = NationalHoliday::findOrFail($id);

        $this->validate($request, [
            'name' => ['required', 'string', 'max:191'],
            'date' => ['required'],
            'religion' => ['required', 'in:Muslim,Christian,Catholic,Hinduism,Buddhism,Confucianism,-'],
        ]);

        $nationalHoliday->name = $request->name;
        $nationalHoliday->date = $request->date;
        $nationalHoliday->religion = $request->religion;
        $nationalHoliday->save();

        $message = Lang::get('National Holiday') . ' \'' . $nationalHoliday->name . '\' ' . Lang::get('successfully updated.');
        return redirect()->route('master.national-holiday.index')->with('status', $message);
    }

    public function destroy($id)
    {
        if (!Laratrust::isAbleTo('delete-master')) {
            return abort(404);
        }

        $nationalHoliday = NationalHoliday::findOrFail($id);
        $name = $nationalHoliday->name;
        $nationalHoliday->delete();

        $message = Lang::get('National Holiday') . ' \'' . $name . '\' ' . Lang::get('successfully deleted.');
        return redirect()->route('master.national-holiday.index')->with('status', $message);
    }
}
