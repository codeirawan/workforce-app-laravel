<?php

namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\LeaveType;
use App\Models\Leave\PaidLeave;
use App\Models\Leave\PaidLeaveStatus;
use App\User;
use DataTables;
use DB, Auth;
use Exception;
use Lang;
use Laratrust;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

class PaidLeaveController extends Controller
{
    public function index()
    {
        if (!Laratrust::isAbleTo('view-leave')) {
            return abort(404);
        }

        return view('leave.paid-leave.index');
    }

    public function data()
    {
        if (!Laratrust::isAbleTo('view-leave')) {
            return abort(404);
        }

        $userId = Auth::user()->id;
        $paidLeaves = PaidLeave::where('by', $userId)->get();

        return DataTables::of($paidLeaves)
        ->editColumn('status', function($paidLeave) {
        $color = $paidLeave->status == 'Draft' ? 'light'
        : ($paidLeave->status == 'Submitted' ? 'warning'
        : ($paidLeave->status == 'Processed' ? 'primary'
        : ($paidLeave->status == 'Approved' ? 'success'
        : ($paidLeave->status == 'Rejected' ? 'danger'
        : ($paidLeave->status == 'Canceled' ? 'dark'
        : 'secondary')))));

        return '<span class="badge badge-' . $color . '">' . Lang::get($paidLeave->status) . '</span>';
        })
        ->addColumn('action', function($paidLeave) {
        $view = '<a href="' . route('paid-leave.show', $paidLeave->id) . '"
            class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('View') . '"><i
                class="la la-eye"></i></a>';
        $edit = '<a href="' . route('paid-leave.edit', $paidLeave->id) . '"
            class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Edit') . '"><i
                class="la la-edit"></i></a>';
        $delete = '<a href="#" data-href="' . route('paid-leave.destroy', $paidLeave->id) . '"
            class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Delete') . '"
            data-toggle="modal" data-target="#modal-delete" data-key="' . $paidLeave->name . '"><i
                class="la la-trash"></i></a>';
        $submit = '<a href="#" data-id="' . $paidLeave->id . '"
            class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Submit') . '"
            data-toggle="modal" data-target="#modal-submit" data-key="' . $paidLeave->name . '"><i
                class="la la-check"></i></a>';
        $process = '<a href="#" data-id="' . $paidLeave->id . '"
            class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Process') . '"
            data-toggle="modal" data-target="#modal-process" data-key="' . $paidLeave->name . '"><i
                class="fa fa-user-check"></i></a>';
        $approve = '<a href="#" data-id="' . $paidLeave->id . '"
            class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Approve') . '"
            data-toggle="modal" data-target="#modal-approve" data-key="' . $paidLeave->name . '"><i
                class="fa fa-user-check"></i></a>';
        $cancel = '<a href="#" data-href="' . route('paid-leave.cancel', $paidLeave->id) . '"
            class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Cancel') . '"
            data-toggle="modal" data-target="#modal-cancel"><i class="la la-ban"></i></a>';

        return $view
        . (Laratrust::isAbleTo('update-leave') && $paidLeave->status == 'Draft' ? $edit : '')
        . (Laratrust::isAbleTo('update-leave') && $paidLeave->status == 'Draft' ? $submit : '')
        . (Laratrust::isAbleTo('process-leave') && $paidLeave->status == 'Submitted' ? $process : '')
        . (Laratrust::isAbleTo('approve-leave') && $paidLeave->status == 'Process' ? $approve : '')
        . (Laratrust::isAbleTo('cancel-leave') && ($paidLeave->status == 'Submitted' || $paidLeave->status == 'Process') ? $cancel : '')
        . (Laratrust::isAbleTo('delete-leave') && $paidLeave->status == 'Draft' ? $delete : '');
        })
        ->rawColumns(['status', 'action'])
        ->make(true);
    }

    public function create()
    {
        if (!Laratrust::isAbleTo('create-leave')) {
            return abort(404);
        }

        $leaveTypes = LeaveType::select('id', 'name')->orderBy('name')->get();

        return view('leave.paid-leave.create', compact('leaveTypes'));
    }

    public function store(Request $request)
    {
        if (!Laratrust::isAbleTo('create-leave')) {
            return abort(404);
        }

        $this->validate($request, [
            'by' => ['required', 'integer'],
            'leave_type' => ['required', 'integer', 'exists:master_leave_types,id'],
            'start_date' => ['required'],
            'end_date' => ['required'],
            'note' => ['string'],
        ]);

        // $email = $request->email;

        // if (User::withoutGlobalScope('active')->whereEmail($email)->exists()) {
        //     return $this->validationError(Lang::get('The email has already been taken.'));
        // }

        DB::beginTransaction();
        try {
            $paidLeave = new PaidLeave;
            $paidLeave->by = $request->by;
            $paidLeave->leave_type = $request->leave_type;
            $paidLeave->start_date = $request->start_date;
            $paidLeave->end_date = $request->end_date;
            $paidLeave->status = 'Draft';
            $paidLeave->save();

            $paidLeaveStatus = new PaidLeaveStatus;
            $paidLeaveStatus->leave_id = $paidLeave->id;
            $paidLeaveStatus->status = 'Draft';
            $paidLeaveStatus->at = now();
            $paidLeaveStatus->by = Auth::user()->id;
            $paidLeaveStatus->note = $request->note;
            $paidLeaveStatus->save();

        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return abort(500);
        }
        DB::commit();

        $message = Lang::get('Leave date') . ' \'' . $paidLeave->start_date . '\' ' . ' \'' . $paidLeave->end_date . '\' ' . Lang::get('successfully created.');
        return redirect()->route('paid-leave.index')->with('status', $message);
    }

    public function show($id)
    {
        if (!Laratrust::isAbleTo('view-leave')) {
            return abort(404);
        }

        $user = User::select('id', 'name', 'email', 'active', 'nik')->withoutGlobalScope('active')->findOrFail($id);
        $role = $user->roles()->select('display_name')->first()->display_name;

        return view('leave.paid-leave.show', compact('user', 'role'));
    }

    public function edit($id)
    {
        if (!Laratrust::isAbleTo('update-leave')) {
            return abort(404);
        }

        $user = User::select('id', 'name', 'email', 'active', 'nik')->withoutGlobalScope('active')->findOrFail($id);
        $userRole = $user->roles()->select('id')->first()->id;
        $roles = Role::select('id', 'display_name')->orderBy('display_name')->get();

        return view('leave.paid-leave.edit', compact('user', 'userRole', 'roles'));
    }

    public function update($id, Request $request)
    {
        if (!Laratrust::isAbleTo('update-leave')) {
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
        if (!Laratrust::isAbleTo('delete-leave')) {
            return abort(404);
        }

        $user = User::withoutGlobalScope('active')->findOrFail($id);
        $email = $user->email;
        $user->delete();

        $message = Lang::get('User') . ' \'' . $email . '\' ' . Lang::get('successfully deleted.');
        return redirect()->route('user.index')->with('status', $message);
    }
}
