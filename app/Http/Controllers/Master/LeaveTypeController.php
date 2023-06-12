<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\LeaveType;
use DataTables;
use Lang;
use Laratrust;

class LeaveTypeController extends Controller
{
    public function index()
    {
        if (!Laratrust::isAbleTo('view-master')) {
            return abort(404);
        }

        return view('master.leave-type.index');
    }

    public function data()
    {
        if (!Laratrust::isAbleTo('view-master')) {
            return abort(404);
        }

        $leaveTypes = LeaveType::select('id', 'name');

        return DataTables::of($leaveTypes)
            ->addColumn('action', function ($leaveType) {
                $edit = '<a href="' . route('master.leave-type.edit', $leaveType->id) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Edit') . '"><i class="la la-edit"></i></a>';
                $delete = '<a href="#" data-href="' . route('master.leave-type.destroy', $leaveType->id) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Delete') . '" data-toggle="modal" data-target="#modal-delete" data-key="' . $leaveType->name . '"><i class="la la-trash"></i></a>';

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

        return view('master.leave-type.create');
    }

    public function store(Request $request)
    {
        if (!Laratrust::isAbleTo('create-master')) {
            return abort(404);
        }

        $this->validate($request, [
            'nama' => ['required', 'string', 'max:255'],
        ]);

        $leaveType = new LeaveType;
        $leaveType->name = $request->nama;
        $leaveType->save();

        $message = Lang::get('Leave type') . ' \'' . $leaveType->name . '\' ' . Lang::get('successfully created.');
        return redirect()->route('master.leave-type.index')->with('status', $message);
    }

    public function edit($id)
    {
        if (!Laratrust::isAbleTo('update-master')) {
            return abort(404);
        }

        $leaveType = LeaveType::select('id', 'name')->findOrFail($id);

        return view('master.leave-type.edit', compact('leaveType'));
    }

    public function update($id, Request $request)
    {
        if (!Laratrust::isAbleTo('update-master')) {
            return abort(404);
        }

        $leaveType = LeaveType::findOrFail($id);

        $this->validate($request, [
            'nama' => ['required', 'string', 'max:255'],
        ]);

        $leaveType->name = $request->nama;
        $leaveType->save();

        $message = Lang::get('Leave type') . ' \'' . $leaveType->name . '\' ' . Lang::get('successfully updated.');
        return redirect()->route('master.leave-type.index')->with('status', $message);
    }

    public function destroy($id)
    {
        if (!Laratrust::isAbleTo('delete-master')) {
            return abort(404);
        }

        $leaveType = LeaveType::findOrFail($id);
        $name = $leaveType->name;
        $leaveType->delete();

        $message = Lang::get('Leave type') . ' \'' . $name . '\' ' . Lang::get('successfully deleted.');
        return redirect()->route('master.leave-type.index')->with('status', $message);
    }
}
