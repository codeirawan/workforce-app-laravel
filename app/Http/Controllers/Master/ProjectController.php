<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laratrust, DataTables, Lang;
use App\Models\Master\Project;

class ProjectController extends Controller
{
    public function index()
    {
        if (!Laratrust::isAbleTo('view-project')) return abort(404);

        return view('master.project.index');
    }

    public function data()
    {
        if (!Laratrust::isAbleTo('view-project')) return abort(404);

        $projects = Project::select('id', 'name');

        return DataTables::of($projects)
            ->addColumn('action', function($project) {
                $edit = '<a href="' . route('master.project.edit', $project->id) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Edit') . '"><i class="la la-edit"></i></a>';
                $delete = '<a href="#" data-href="' . route('master.project.destroy', $project->id) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Delete') . '" data-toggle="modal" data-target="#modal-delete" data-key="' . $project->name . '"><i class="la la-trash"></i></a>';

                return (Laratrust::isAbleTo('update-project') ? $edit : '') . (Laratrust::isAbleTo('delete-project') ? $delete : '');
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        if (!Laratrust::isAbleTo('create-project')) return abort(404);

        return view('master.project.create');
    }

    public function store(Request $request)
    {
        if (!Laratrust::isAbleTo('create-project')) return abort(404);

        $this->validate($request, [
            'nama' => ['required', 'string', 'max:255'],
        ]);

        $project = new Project;
        $project->name = $request->nama;
        $project->save();

        $message = Lang::get('Project') . ' \'' . $project->name . '\' ' . Lang::get('successfully created.');
        return redirect()->route('master.project.index')->with('status', $message);
    }

    public function edit($id)
    {
        if (!Laratrust::isAbleTo('update-project')) return abort(404);

        $project = Project::select('id', 'name')->findOrFail($id);

        return view('master.project.edit', compact('project'));
    }

    public function update($id, Request $request)
    {
        if (!Laratrust::isAbleTo('update-project')) return abort(404);

        $project = Project::findOrFail($id);

        $this->validate($request, [
            'nama' => ['required', 'string', 'max:255'],
        ]);

        $project->name = $request->nama;
        $project->save();

        $message = Lang::get('Project') . ' \'' . $project->name . '\' ' . Lang::get('successfully updated.');
        return redirect()->route('master.project.index')->with('status', $message);
    }

    public function destroy($id)
    {
        if (!Laratrust::isAbleTo('delete-project')) return abort(404);

        $project = Project::findOrFail($id);
        $name = $project->name;
        $project->delete();

        $message = Lang::get('Project') . ' \'' . $name . '\' ' . Lang::get('successfully deleted.');
        return redirect()->route('master.project.index')->with('status', $message);
    }
}
