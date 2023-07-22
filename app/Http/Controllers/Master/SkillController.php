<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\City;
use App\Models\Master\Project;
use App\Models\Master\Shift;
use App\Models\Master\ShiftSkill;
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

        $skills = Skill::select(
            'master_cities.name AS site',
            'master_projects.name AS project',
            'master_skills.name AS skill',
            'master_skills.id'
        )
            ->leftJoin('master_cities', 'master_cities.id', '=', 'master_skills.city_id')
            ->leftJoin('master_projects', 'master_projects.id', '=', 'master_skills.project_id')->get();

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

        $cities = City::select('id', 'name')->orderBy('name')->whereIn('id', ['3171', '3374', '3471', '3372'])->get();
        $projects = Project::select('id', 'name')->orderBy('name')->get();
        $shifting = Shift::select('id', 'name', 'start_time', 'end_time')->orderBy('name')->get();

        return view('master.skill.create', compact('cities', 'projects', 'shifting'));
    }

    public function store(Request $request)
    {
        if (!Laratrust::isAbleTo('create-master')) {
            return abort(404);
        }

        $this->validate($request, [
            'name' => ['required', 'string', 'max:191'],
            'city_id' => ['nullable', 'integer', 'exists:master_cities,id'],
            'project_id' => ['nullable', 'integer', 'exists:master_projects,id'],
        ]);

        $skill = new Skill;
        $skill->city_id = $request->city_id;
        $skill->project_id = $request->project_id;
        $skill->name = $request->name;
        $skill->save();

        // Handle shift skills
        if ($request->has('shift_skill')) {
            $shiftSkills = $request->input('shift_skill');

            // Assuming shift_skill is an array of shift IDs
            foreach ($shiftSkills as $shiftId) {
                // Create and save a new ShiftSkill record for the current skill
                ShiftSkill::create([
                    'skill_id' => $skill->id,
                    'shift_id' => $shiftId,
                ]);
            }
        }

        $message = Lang::get('Skill') . ' \'' . $skill->name . '\' ' . Lang::get('successfully created.');
        return redirect()->route('master.skill.index')->with('status', $message);
    }

    public function edit($id)
    {
        if (!Laratrust::isAbleTo('update-master')) {
            return abort(404);
        }
        $cities = City::select('id', 'name')->orderBy('name')->whereIn('id', ['3171', '3374', '3471', '3372'])->get();
        $projects = Project::select('id', 'name')->orderBy('name')->get();
        $skill = Skill::select('id', 'name', 'project_id', 'city_id')->findOrFail($id);
        $shifting = Shift::select('id', 'name', 'start_time', 'end_time')->orderBy('name')->get();
        $shiftSkills = ShiftSkill::where('skill_id', $id)->pluck('shift_id')->toArray();

        return view('master.skill.edit', compact('cities', 'projects', 'skill', 'shifting', 'shiftSkills'));
    }

    public function update($id, Request $request)
    {
        if (!Laratrust::isAbleTo('update-master')) {
            return abort(404);
        }

        $skill = Skill::findOrFail($id);

        $this->validate($request, [
            'name' => ['required', 'string', 'max:191'],
            'city_id' => ['nullable', 'integer', 'exists:master_cities,id'],
            'project_id' => ['nullable', 'integer', 'exists:master_projects,id'],
        ]);

        $skill->city_id = $request->city_id;
        $skill->project_id = $request->project_id;
        $skill->name = $request->name;
        $skill->save();

        // Handle shift skills
        if ($request->has('shift_skill')) {
            $shiftSkills = $request->input('shift_skill');

            // Update shift skills for the current skill
            ShiftSkill::where('skill_id', $id)->delete(); // Remove existing shift skills

            // Assuming shift_skill is an array of shift IDs
            foreach ($shiftSkills as $shiftId) {
                // Create and save a new ShiftSkill record for the current skill
                ShiftSkill::create([
                    'skill_id' => $id,
                    'shift_id' => $shiftId,
                ]);
            }
        }

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