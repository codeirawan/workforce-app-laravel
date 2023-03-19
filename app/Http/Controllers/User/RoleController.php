<?php

namespace App\Http\Controllers\User;

use App\Role;
use App\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laratrust, Lang, DataTables, DB, Exception, Str;

class RoleController extends Controller
{
    public function index()
    {
        if (!Laratrust::isAbleTo('view-role')) return abort(404);

        return view('user.role.index');
    }

    public function data()
    {
        if (!Laratrust::isAbleTo('view-role')) return abort(404);

        $roles = Role::select('id', 'display_name');

        return DataTables::of($roles)
            ->addColumn('action', function($role) {
                $view = '<a href="' . route('role.show', $role->id) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('View') . '"><i class="la la-eye"></i></a>';
                $edit = '<a href="' . route('role.edit', $role->id) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Edit') . '"><i class="la la-edit"></i></a>';

                return (Laratrust::isAbleTo('view-role') ? $view : '') . (Laratrust::isAbleTo('update-role') ? $edit : '');
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        if (!Laratrust::isAbleTo('create-role')) return abort(404);

        $permissionGroups = Permission::select('id', 'display_name', 'group')->get()->groupBy('group');

        return view('user.role.create', compact('permissionGroups'));
    }

    public function store(Request $request)
    {
        if (!Laratrust::isAbleTo('create-role')) return abort(404);

        $this->validate($request, [
            'nama' => 'required|alpha_dash|unique:roles,name',
            'hak_akses.*.*' => 'nullable|integer|exists:permissions,id',
        ]);

        $permissions = [];
        foreach ($request->input('hak_akses', []) as $group => $permissionIds) {
            foreach ($permissionIds as $permissionId) {
                array_push($permissions, $permissionId);
            }
        }

        $displayName = str_replace('-', ' ', $request->nama);
        $displayName = str_replace('_', ' ', $displayName);

        DB::beginTransaction();
        try {
            $role = new Role;
            $role->name = $request->nama;
            $role->display_name = Str::title($displayName);
            $role->save();

            $role->syncPermissions($permissions);
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return abort(500);
        }
        DB::commit();

        $message = Lang::get('Role') . ' \'' . $role->display_name . '\' ' . Lang::get('successfully created.');
        return redirect()->route('role.index')->with('status', $message);
    }

    public function show($id)
    {
        if (!Laratrust::isAbleTo('view-role')) return abort(404);

        $role = Role::select('id', 'name', 'display_name')->findOrFail($id);
        $rolePermissions = $role->permissions()->select('id')->pluck('id')->toArray();
        $permissionGroups = Permission::select('id', 'display_name', 'group')->get()->groupBy('group');

        return view('user.role.show', compact('role', 'rolePermissions', 'permissionGroups'));
    }

    public function edit($id)
    {
        if (!Laratrust::isAbleTo('update-role')) return abort(404);

        $role = Role::select('id', 'name', 'display_name')->findOrFail($id);
        $rolePermissions = $role->permissions()->select('id')->pluck('id')->toArray();
        $permissionGroups = Permission::select('id', 'display_name', 'group')->get()->groupBy('group');

        return view('user.role.edit', compact('role', 'rolePermissions', 'permissionGroups'));
    }

    public function update($id, Request $request)
    {
        if (!Laratrust::isAbleTo('update-role')) return abort(404);

        $role = Role::findOrFail($id);

        $this->validate($request, [
            'nama' => 'required|alpha_dash|unique:roles,name,' . $id,
            'hak_akses.*.*' => 'nullable|integer|exists:permissions,id',
        ]);

        $permissions = [];
        foreach ($request->input('hak_akses', []) as $group => $permissionIds) {
            foreach ($permissionIds as $permissionId) {
                array_push($permissions, $permissionId);
            }
        }

        $displayName = str_replace('-', ' ', $request->nama);
        $displayName = str_replace('_', ' ', $displayName);

        DB::beginTransaction();
        try {
            $role->name = $request->nama;
            $role->display_name = Str::title($displayName);
            $role->save();

            $role->syncPermissions($permissions);
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return abort(500);
        }
        DB::commit();

        $message = Lang::get('Role') . ' \'' . $role->display_name . '\' ' . Lang::get('successfully updated.');
        return redirect()->route('role.index')->with('status', $message);
    }
}
