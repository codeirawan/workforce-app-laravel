<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Skill;
use DataTables;
use Illuminate\Http\Request;
use Lang;
use Laratrust;

class SkillController extends Controller
{
    public function index()
    {
        if (!Laratrust::isAbleTo('view-master')) {
            return abort(404);
        }

        return view('master.skill.index');
    }

    public function data()
    {
        if (!Laratrust::isAbleTo('view-master')) {
            return abort(404);
        }

        $skills = Skill::select('id', 'name');

        return DataTables::of($skills)
            ->addColumn('action', function ($skill) {
                $edit = '<a href="' . route('master.skill.edit', $skill->id) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Edit') . '"><i class="la la-edit"></i></a>';
                $delete = '<a href="#" data-href="' . route('master.skill.destroy', $skill->id) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Delete') . '" data-toggle="modal" data-target="#modal-delete" data-key="' . $skill->name . '"><i class="la la-trash"></i></a>';

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

        return view('master.skill.create');
    }

    public function store(Request $request)
    {
        if (!Laratrust::isAbleTo('create-master')) {
            return abort(404);
        }

        $this->validate($request, [
            'nama' => ['required', 'string', 'max:191'],
        ]);

        $skill = new Skill;
        $skill->name = $request->nama;
        $skill->save();

        $message = Lang::get('Skill') . ' \'' . $skill->name . '\' ' . Lang::get('successfully created.');
        return redirect()->route('master.skill.index')->with('status', $message);
    }

    public function edit($id)
    {
        if (!Laratrust::isAbleTo('update-master')) {
            return abort(404);
        }

        $skill = Skill::select('id', 'name')->findOrFail($id);

        return view('master.skill.edit', compact('skill'));
    }

    public function update($id, Request $request)
    {
        if (!Laratrust::isAbleTo('update-master')) {
            return abort(404);
        }

        $skill = Skill::findOrFail($id);

        $this->validate($request, [
            'nama' => ['required', 'string', 'max:191'],
        ]);

        $skill->name = $request->nama;
        $skill->save();

        $message = Lang::get('Skill') . ' \'' . $skill->name . '\' ' . Lang::get('successfully updated.');
        return redirect()->route('master.skill.index')->with('status', $message);
    }

    public function destroy($id)
    {
        if (!Laratrust::isAbleTo('delete-master')) {
            return abort(404);
        }

        $skill = Skill::findOrFail($id);
        $name = $skill->name;
        $skill->delete();

        $message = Lang::get('Skill') . ' \'' . $name . '\' ' . Lang::get('successfully deleted.');
        return redirect()->route('master.skill.index')->with('status', $message);
    }
}
