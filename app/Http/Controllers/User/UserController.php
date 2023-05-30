<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Imports\BulkUserImport;
use App\Models\Master\Project;
use App\Models\Master\Skill;
use App\Role;
use App\User;
use DataTables;
use DB;
use Exception;
use Illuminate\Http\Request;
use Lang;
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

        $roles = Role::select('id', 'display_name')->orderBy('display_name')->get();

        $projects = Project::select('id', 'name')->orderBy('name')->get();

        $skills = Skill::select('id', 'name')->orderBy('name')->get();

        return view('user.index', compact('roles', 'projects', 'skills'));
    }

    public function data()
    {
        if (!Laratrust::isAbleTo('view-user')) {
            return abort(404);
        }

        $users = User::select('users.id', 'users.name', 'users.email', 'users.active', 'roles.display_name AS role', 'users.nik')
            ->leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
            ->leftJoin('roles', 'roles.id', '=', 'role_user.role_id')
            ->withoutGlobalScope('active');

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

        $roles = Role::select('id', 'display_name')->orderBy('display_name')->get();

        return view('user.create', compact('roles'));
    }

    public function store(Request $request)
    {
        if (!Laratrust::isAbleTo('create-user')) {
            return abort(404);
        }

        $this->validate($request, [
            'nama' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'numeric'],
            'email' => ['required', 'string', 'max:255'],
            'wewenang' => ['required', 'integer', 'exists:roles,id'],
            'kata_sandi' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $email = $request->email;

        if (User::withoutGlobalScope('active')->whereEmail($email)->exists()) {
            return $this->validationError(Lang::get('The email has already been taken.'));
        }

        if (User::withoutGlobalScope('active')->whereNik($request->nik)->exists()) {
            return $this->validationError(Lang::get('The NIK has already been taken.'));
        }

        DB::beginTransaction();
        try {
            $user = new User;
            $user->name = $request->nama;
            $user->nik = $request->nik;
            $user->email = $email;
            $user->password = bcrypt($request->kata_sandi);
            $user->save();

            $user->syncRoles([$request->wewenang]);
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

        $user = User::select('id', 'name', 'email', 'active', 'nik')->withoutGlobalScope('active')->findOrFail($id);
        $role = $user->roles()->select('display_name')->first()->display_name;

        return view('user.show', compact('user', 'role'));
    }

    public function edit($id)
    {
        if (!Laratrust::isAbleTo('update-user')) {
            return abort(404);
        }

        $user = User::select('id', 'name', 'email', 'active', 'nik')->withoutGlobalScope('active')->findOrFail($id);
        $userRole = $user->roles()->select('id')->first()->id;
        $roles = Role::select('id', 'display_name')->orderBy('display_name')->get();

        return view('user.edit', compact('user', 'userRole', 'roles'));
    }

    public function update($id, Request $request)
    {
        if (!Laratrust::isAbleTo('update-user')) {
            return abort(404);
        }

        $user = User::withoutGlobalScope('active')->findOrFail($id);

        $this->validate($request, [
            'nama' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'numeric'],
            'wewenang' => ['required', 'integer', 'exists:roles,id'],
        ]);

        if (User::withoutGlobalScope('active')->whereNik($request->nik)->where('id', '<>', $id)->exists()) {
            return $this->validationError(Lang::get('The NIK has already been taken.'));
        }

        DB::beginTransaction();
        try {
            $user->name = $request->nama;
            $user->nik = $request->nik;
            $user->active = $request->aktif ? 1 : 0;
            $user->save();

            $user->syncRoles([$request->wewenang]);
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
