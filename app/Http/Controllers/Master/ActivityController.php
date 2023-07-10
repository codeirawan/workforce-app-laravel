<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Activity;
use DataTables;
use Illuminate\Http\Request;
use Lang;
use Laratrust;

class ActivityController extends Controller
{
    public function index()
    {
        if (!Laratrust::isAbleTo('view-master')) {
            return abort(404);
        }

        return view('master.activity.index');
    }

    public function data()
    {
        if (!Laratrust::isAbleTo('view-master')) {
            return abort(404);
        }

        $activities = Activity::select('id', 'name', 'duration', 'color');

        return DataTables::of($activities)
            ->addColumn('action', function ($activity) {
                $edit = '<a href="' . route('master.activity.edit', $activity->id) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Edit') . '"><i class="la la-edit"></i></a>';
                $delete = '<a href="#" data-href="' . route('master.activity.destroy', $activity->id) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Delete') . '" data-toggle="modal" data-target="#modal-delete" data-key="' . $activity->name . '"><i class="la la-trash"></i></a>';

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

        return view('master.activity.create');
    }

    public function store(Request $request)
    {
        if (!Laratrust::isAbleTo('create-master')) {
            return abort(404);
        }

        $this->validate($request, [
            'name' => ['required', 'string', 'max:191'],
            'duration' => ['required'],
            'color' => ['required'],
        ]);

        $activity = new Activity;
        $activity->name = $request->name;
        $activity->duration = $request->duration;
        $activity->color = $request->color;
        $activity->save();

        $message = Lang::get('Activity') . ' \'' . $activity->name . '\' ' . Lang::get('successfully created.');
        return redirect()->route('master.activity.index')->with('status', $message);
    }

    public function edit($id)
    {
        if (!Laratrust::isAbleTo('update-master')) {
            return abort(404);
        }

        $activity = Activity::select('id', 'name', 'duration', 'color')->findOrFail($id);

        return view('master.activity.edit', compact('activity'));
    }

    public function update($id, Request $request)
    {
        if (!Laratrust::isAbleTo('update-master')) {
            return abort(404);
        }

        $activity = Activity::findOrFail($id);

        $this->validate($request, [
            'name' => ['required', 'string', 'max:191'],
            'duration' => ['required'],
            'color' => ['required'],
        ]);

        $activity->name = $request->name;
        $activity->duration = $request->duration;
        $activity->color = $request->color;
        $activity->save();

        $message = Lang::get('Activity') . ' \'' . $activity->name . '\' ' . Lang::get('successfully updated.');
        return redirect()->route('master.activity.index')->with('status', $message);
    }

    public function destroy($id)
    {
        if (!Laratrust::isAbleTo('delete-master')) {
            return abort(404);
        }

        $activity = Activity::findOrFail($id);
        $name = $activity->name;
        $activity->delete();

        $message = Lang::get('Activity') . ' \'' . $name . '\' ' . Lang::get('successfully deleted.');
        return redirect()->route('master.activity.index')->with('status', $message);
    }
}
