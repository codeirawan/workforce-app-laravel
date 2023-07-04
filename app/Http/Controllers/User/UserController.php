<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Imports\BulkUserImport;
use App\Models\Master\City;
use App\Models\Master\Project;
use App\Models\Master\Skill;
use App\Role;
use App\User;
use DataTables;
use DB;
use Exception;
use Illuminate\Http\Request;
use Lang, Str;
use Laratrust;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

class UserController extends Controller
{
    public function index()
    {
        if (!Laratrust::isAbleTo('view-user')) {
            return abort(404);
        }

        $roles = Role::select('id', 'display_name')->orderBy('id')->where(
            'name',
            '!=',
            'super_administrator'
        )->get();

        return view('user.index', compact('roles'));
    }

    public function data()
    {
        if (!Laratrust::isAbleTo('view-user')) {
            return abort(404);
        }

        $users = User::select(
            'users.id',
            'users.nik',
            'users.name',
            'users.email',
            'users.gender',
            'users.religion',
            'users.join_date',
            'users.initial_leave',
            'users.used_leave',
            'users.team_leader_id',
            'users.team_leader_name',
            'users.supervisor_id',
            'users.supervisor_name',
            'master_cities.name AS site',
            'master_projects.name AS project',
            'master_skills.name AS skill',
            'roles.display_name AS role',
            'users.active',
        )
            ->leftJoin('master_cities', 'master_cities.id', '=', 'users.city_id')
            ->leftJoin('master_projects', 'master_projects.id', '=', 'users.project_id')
            ->leftJoin('master_skills', 'master_skills.id', '=', 'users.skill_id')
            ->leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
            ->leftJoin('roles', 'roles.id', '=', 'role_user.role_id')
            ->withoutGlobalScope('active')
            ->where('roles.name', '!=', 'super_administrator')
            ->get();

        return DataTables::of($users)
            ->editColumn('active', function ($user) {
                return $user->active
                    ? '<i class="la la-check text-success font-weight-bold"></i>'
                    : '<i class="la la-times text-danger font-weight-bold"></i>';
            })
            ->addColumn('action', function ($user) {
                $view = '<a href="' . route('user.show', $user->id) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('View') . '"><i class="la la-eye"></i></a>';
                $edit = '<a href="' . route('user.edit', $user->id) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Edit') . '"><i class="la la-edit"></i></a>';
                $delete = '<a href="#" data-href="' . route('user.destroy', $user->id) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Delete') . '" data-toggle="modal" data-target="#modal-delete" data-key="' . $user->email . '"><i class="la la-trash"></i></a>';

                return (Laratrust::isAbleTo('view-user') ? $view : '') . (Laratrust::isAbleTo('update-user') ? $edit : '') . (Laratrust::isAbleTo('delete-user') ? $delete : '');
            })
            ->rawColumns(['active', 'action'])
            ->make(true);
    }

    public function bulk(Request $request)
    {
        $validator = Validator::make(
            [
                'file' => $request->file,
                'extension' => strtolower($request->file->getClientOriginalExtension()),
            ],
            [
                'file' => 'required',
                'extension' => 'required|in:xlsx,xls',
            ]
        );

        if ($validator->fails()) {
            return back()->with('danger', 'Bulk user upload failed, the file must be a file of type: xls/xlsx.');
        }

        if ($request->hasFile('file')) {
            Excel::import(
                new BulkUserImport(),
                $request->file('file')->store('files')
            );
        }

        return back()->with('success', 'Bulk user upload was successfully!');
    }

    public function create()
    {
        if (!Laratrust::isAbleTo('create-user')) {
            return abort(404);
        }

        $roles = Role::select('id', 'display_name')->orderBy('id')->where(
            'name',
            '!=',
            'super_administrator'
        )->get();
        $cities = City::select('id', 'name')->orderBy('name')->whereIn('id', ['3171', '3374', '3471', '3372'])->get();
        $projects = Project::select('id', 'name')->orderBy('name')->get();
        $skills = Skill::select('id', 'name')->orderBy('name')->get();

        return view('user.create', compact('roles', 'cities', 'projects', 'skills'));
    }

    public function store(Request $request)
    {
        if (!Laratrust::isAbleTo('create-user')) {
            return abort(404);
        }

        $this->validate($request, [
            'name' => ['required', 'string', 'max:191'],
            'role' => ['required', 'integer', 'exists:roles,id'],
            'nik' => ['required', 'string', 'max:191'],
            'email' => ['required', 'string', 'max:191'],
            'gender' => ['nullable', 'in:Male,Female'],
            'religion' => ['nullable', 'in:Muslim,Christian,Hinduism,Buddhism,Confucianism,Other'],
            'city_id' => ['nullable', 'integer', 'exists:master_cities,id'],
            'project_id' => ['nullable', 'integer', 'exists:master_projects,id'],
            'skill_id' => ['nullable', 'integer', 'exists:master_skills,id'],
            'team_leader' => ['nullable', 'string', 'max:191'],
            'supervisor' => ['nullable', 'string', 'max:191'],
            'join_date' => ['nullable', 'date_format:Y-m-d'],
            'initial_leave' => ['nullable', 'integer'],
            'used_leave' => ['nullable', 'integer'],
        ]);

        if (User::withoutGlobalScope('active')->whereEmail($request->email)->exists()) {
            return $this->validationError(Lang::get('The email has already been taken.'));
        }

        if (User::withoutGlobalScope('active')->whereNik($request->nik)->exists()) {
            return $this->validationError(Lang::get('The user ID has already been taken.'));
        }

        if ($request->team_leader && $request->team_leader !== null) {
            $team_leader = User::where('name', $request->team_leader)->first();

            if ($team_leader === null) {
                return $this->validationError(Lang::get("The team leader's name was not found in the records."));
            }
        }

        if ($request->supervisor && $request->supervisor !== null) {
            $supervisor = User::where('name', $request->supervisor)->first();

            if ($supervisor === null) {
                return $this->validationError(Lang::get("The supervisor's name was not found in the records."));
            }
        }

        DB::beginTransaction();
        try {
            $user = new User;
            $user->name = Str::title($request->name);
            $user->nik = $request->nik;
            $user->email = Str::lower($request->email);
            $user->password = bcrypt('Pa$$w0rd!');
            $user->gender = $request->gender;
            $user->religion = $request->religion;
            $user->city_id = $request->city_id;
            $user->project_id = $request->project;
            $user->skill_id = $request->skill;

            if ($request->team_leader !== null && $team_leader !== null) {
                $user->team_leader_id = $team_leader->nik;
                $user->team_leader_name = $request->team_leader;
            } else {
                $user->team_leader_id = null;
                $user->team_leader_name = null;
            }

            if ($request->supervisor !== null && $supervisor !== null) {
                $user->supervisor_id = $supervisor->nik;
                $user->supervisor_name = $request->supervisor;
            } else {
                $user->supervisor_id = null;
                $user->supervisor_name = null;
            }

            if ($request->join_date || $request->initial_leave || $request->used_leave) {
                $user->join_date = $request->join_date;
                $user->initial_leave = $request->initial_leave;
                $user->used_leave = $request->used_leave;
            }

            $user->save();

            $user->syncRoles([$request->role]);
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return abort(500);
        }
        DB::commit();

        $user->sendEmailVerificationNotification();

        $message = Lang::get('User') . ' \'' . $user->email . '\' ' . Lang::get('successfully created.');
        return redirect()->route('user.index')->with('status', $message);
    }

    public function show($id)
    {
        if (!Laratrust::isAbleTo('view-user')) {
            return abort(404);
        }

        $user = User::select(
            'users.id',
            'users.nik',
            'users.name',
            'users.email',
            'users.gender',
            'users.religion',
            'users.join_date',
            'users.initial_leave',
            'users.used_leave',
            'users.team_leader_id',
            'users.team_leader_name',
            'users.supervisor_id',
            'users.supervisor_name',
            'master_cities.name AS site',
            'master_projects.name AS project',
            'master_skills.name AS skill',
            'roles.display_name AS role',
            'users.active',
        )
            ->leftJoin('master_cities', 'master_cities.id', '=', 'users.city_id')
            ->leftJoin('master_projects', 'master_projects.id', '=', 'users.project_id')
            ->leftJoin('master_skills', 'master_skills.id', '=', 'users.skill_id')
            ->leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
            ->leftJoin('roles', 'roles.id', '=', 'role_user.role_id')
            ->withoutGlobalScope('active')->findOrFail($id);

        $role = $user->roles()->select('display_name')->first()->display_name;

        return view('user.show', compact('user', 'role'));
    }

    public function edit($id)
    {
        if (!Laratrust::isAbleTo('update-user')) {
            return abort(404);
        }

        $user = User::select(
            'users.id',
            'users.nik',
            'users.name',
            'users.email',
            'users.gender',
            'users.religion',
            'users.join_date',
            'users.initial_leave',
            'users.used_leave',
            'users.team_leader_id',
            'users.team_leader_name',
            'users.supervisor_id',
            'users.supervisor_name',
            'users.city_id',
            'users.project_id',
            'users.skill_id',
            'master_cities.name AS site',
            'master_projects.name AS project',
            'master_skills.name AS skill',
            'roles.display_name AS role',
            'users.active',
        )
            ->leftJoin('master_cities', 'master_cities.id', '=', 'users.city_id')
            ->leftJoin('master_projects', 'master_projects.id', '=', 'users.project_id')
            ->leftJoin('master_skills', 'master_skills.id', '=', 'users.skill_id')
            ->leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
            ->leftJoin('roles', 'roles.id', '=', 'role_user.role_id')
            ->withoutGlobalScope('active')->findOrFail($id);

        $userRole = $user->roles()->select('id')->first()->id;
        $roles = Role::select('id', 'display_name')->orderBy('id')->where(
            'name',
            '!=',
            'super_administrator'
        )->get();
        $cities = City::select('id', 'name')->orderBy('name')->whereIn('id', ['3171', '3374', '3471', '3372'])->get();
        $projects = Project::select('id', 'name')->orderBy('name')->get();
        $skills = Skill::select('id', 'name')->orderBy('name')->get();

        return view('user.edit', compact('user', 'userRole', 'roles', 'cities', 'projects', 'skills'));
    }

    public function update($id, Request $request)
    {
        if (!Laratrust::isAbleTo('update-user')) {
            return abort(404);
        }

        $user = User::withoutGlobalScope('active')->findOrFail($id);

        $this->validate($request, [
            'name' => ['required', 'string', 'max:191'],
            'role' => ['required', 'integer', 'exists:roles,id'],
            'nik' => ['required', 'string', 'max:191'],
            // 'email' => ['required', 'string', 'max:191'],
            'gender' => ['nullable', 'in:Male,Female'],
            'religion' => ['nullable', 'in:Muslim,Christian,Hinduism,Buddhism,Confucianism,Other'],
            'city_id' => ['nullable', 'integer', 'exists:master_cities,id'],
            'project_id' => ['nullable', 'integer', 'exists:master_projects,id'],
            'skill_id' => ['nullable', 'integer', 'exists:master_skills,id'],
            'team_leader' => ['nullable', 'string', 'max:191'],
            'supervisor' => ['nullable', 'string', 'max:191'],
            'join_date' => ['nullable', 'date_format:Y-m-d'],
            'initial_leave' => ['nullable', 'integer'],
            'used_leave' => ['nullable', 'integer'],
        ]);

        if (User::withoutGlobalScope('active')->whereNik($request->nik)->where('id', '<>', $id)->exists()) {
            return $this->validationError(Lang::get('The user ID has already been taken.'));
        }

        if ($request->team_leader && $request->team_leader !== null) {
            $team_leader = User::where('name', $request->team_leader)->first();

            if ($team_leader === null) {
                return $this->validationError(Lang::get("The team leader's name was not found in the records."));
            }
        }

        if ($request->supervisor && $request->supervisor !== null) {
            $supervisor = User::where('name', $request->supervisor)->first();

            if ($supervisor === null) {
                return $this->validationError(Lang::get("The supervisor's name was not found in the records."));
            }
        }

        DB::beginTransaction();
        try {
            $user->name = $request->name;
            $user->nik = $request->nik;
            $user->gender = $request->gender;
            $user->religion = $request->religion;
            $user->city_id = $request->city;
            $user->project_id = $request->project;
            $user->skill_id = $request->skill;

            if ($request->team_leader !== null && $team_leader !== null) {
                $user->team_leader_id = $team_leader->nik;
                $user->team_leader_name = $request->team_leader;
            } else {
                $user->team_leader_id = null;
                $user->team_leader_name = null;
            }

            if ($request->supervisor !== null && $supervisor !== null) {
                $user->supervisor_id = $supervisor->nik;
                $user->supervisor_name = $request->supervisor;
            } else {
                $user->supervisor_id = null;
                $user->supervisor_name = null;
            }

            if ($request->join_date || $request->initial_leave || $request->used_leave) {
                $user->join_date = $request->join_date;
                $user->initial_leave = $request->initial_leave;
                $user->used_leave = $request->used_leave;
            }

            $user->active = $request->aktif ? 1 : 0;
            $user->save();

            $user->syncRoles([$request->role]);
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return abort(500);
        }
        DB::commit();

        $message = Lang::get('User') . ' \'' . $user->email . '\' ' . Lang::get('successfully updated.');
        return redirect()->route('user.index')->with('status', $message);
    }

    public function destroy($id)
    {
        if (!Laratrust::isAbleTo('delete-user')) {
            return abort(404);
        }

        $user = User::withoutGlobalScope('active')->findOrFail($id);
        $email = $user->email;
        $user->delete();

        $message = Lang::get('User') . ' \'' . $email . '\' ' . Lang::get('successfully deleted.');
        return redirect()->route('user.index')->with('status', $message);
    }
}
