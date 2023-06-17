<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Shift;
use DataTables;
use Illuminate\Http\Request;
use Lang;
use Laratrust;

class ShiftController extends Controller
{
    public function index()
    {
        if (!Laratrust::isAbleTo('view-master')) {
            return abort(404);
        }

        return view('master.shift.index');
    }

    public function data()
    {
        if (!Laratrust::isAbleTo('view-master')) {
            return abort(404);
        }

        $shifting = Shift::select('id', 'name', 'start_time', 'end_time');

        return DataTables::of($shifting)
            ->addColumn('action', function ($shift) {
                $edit = '<a href="' . route('master.shift.edit', $shift->id) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Edit') . '"><i class="la la-edit"></i></a>';
                $delete = '<a href="#" data-href="' . route('master.shift.destroy', $shift->id) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Delete') . '" data-toggle="modal" data-target="#modal-delete" data-key="' . $shift->name . '"><i class="la la-trash"></i></a>';

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

        return view('master.shift.create');
    }

    public function store(Request $request)
    {
        if (!Laratrust::isAbleTo('create-master')) {
            return abort(404);
        }

        $this->validate($request, [
            'name' => ['required', 'string', 'unique:master_shift', 'max:191'],
            'start_time' => ['required'],
            'end_time' => ['required'],
        ]);

        $shift = new Shift;
        $shift->name = $request->name;
        $shift->start_time = $request->start_time;
        $shift->end_time = $request->end_time;
        $shift->save();

        $message = Lang::get('Shift') . ' \'' . $shift->name . '\' ' . Lang::get('successfully created.');
        return redirect()->route('master.shift.index')->with('status', $message);
    }

    public function edit($id)
    {
        if (!Laratrust::isAbleTo('update-master')) {
            return abort(404);
        }

        $shift = Shift::select('id', 'name', 'start_time', 'end_time')->findOrFail($id);

        return view('master.shift.edit', compact('shift'));
    }

    public function update($id, Request $request)
    {
        if (!Laratrust::isAbleTo('update-master')) {
            return abort(404);
        }

        $shift = Shift::findOrFail($id);

        $this->validate($request, [
            'name' => ['required', 'string', 'unique:master_shift,name,' . $shift->id, 'max:191'],
            'start_time' => ['required'],
            'end_time' => ['required'],
        ]);

        $shift->name = $request->name;
        $shift->start_time = $request->start_time;
        $shift->end_time = $request->end_time;
        $shift->save();

        $message = Lang::get('Shift') . ' \'' . $shift->name . '\' ' . Lang::get('successfully updated.');
        return redirect()->route('master.shift.index')->with('status', $message);
    }

    public function destroy($id)
    {
        if (!Laratrust::isAbleTo('delete-master')) {
            return abort(404);
        }

        $shift = Shift::findOrFail($id);
        $name = $shift->name;
        $shift->delete();

        $message = Lang::get('Shift') . ' \'' . $name . '\' ' . Lang::get('successfully deleted.');
        return redirect()->route('master.shift.index')->with('status', $message);
    }
}
