<?php

namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\LeaveType;
use App\Models\Leave\PaidLeave;
use App\Models\Leave\PaidLeaveStatus;
use DataTables;
use DB, Auth;
use Exception;
use Lang;
use Laratrust;
use Str;

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
        $paidLeaves = PaidLeave::select(
            'paid_leave.id',
            'request_id',
            'users.name AS name',
            'master_leave_types.name AS type',
            'start_date',
            'end_date',
            'note',
            'status',
            'paid_leave.created_at'
        )
            ->leftJoin('users', 'users.id', '=', 'paid_leave.by')
            ->leftJoin('master_leave_types', 'master_leave_types.id', '=', 'paid_leave.leave_type')
            ->where('by', $userId)
            ->get();

        return DataTables::of($paidLeaves)
            ->editColumn('status', function ($paidLeave) {
                $color = $paidLeave->status == 'Draft' ? 'light'
                    : ($paidLeave->status == 'Submitted' ? 'warning'
                        : ($paidLeave->status == 'Processed' ? 'primary'
                            : ($paidLeave->status == 'Approved' ? 'success'
                                : ($paidLeave->status == 'Rejected' ? 'danger'
                                    : ($paidLeave->status == 'Canceled' ? 'dark'
                                        : 'secondary')))));

                return '<span class="badge badge-' . $color . '">' . Lang::get($paidLeave->status) . '</span>';
            })
            ->addColumn('action', function ($paidLeave) {
                $view = '<a href="' . route('paid-leave.show', $paidLeave->id) . '"
            class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('View') . '"><i
                class="la la-eye"></i></a>';
                $edit = '<a href="' . route('paid-leave.edit', $paidLeave->id) . '"
            class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Edit') . '"><i
                class="la la-edit"></i></a>';
                $delete = '<a href="#" data-href="' . route('paid-leave.destroy', $paidLeave->id) . '"
            class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Delete') . '"
            data-toggle="modal" data-target="#modal-delete" data-key="' . $paidLeave->request_id . '"><i
                class="la la-trash"></i></a>';
                $submit = '<a href="#" data-id="' . $paidLeave->id . '"
            class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Submit') . '"
            data-toggle="modal" data-target="#modal-submit" data-key="' . $paidLeave->request_id . '"><i
                class="fa-regular fa-paper-plane"></i></a>';
                $process = '<a href="#" data-id="' . $paidLeave->id . '"
            class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Process') . '"
            data-toggle="modal" data-target="#modal-process" data-key="' . $paidLeave->request_id . '"><i
                class="fa fa-check"></i></a>';
                $approve = '<a href="#" data-id="' . $paidLeave->id . '"
            class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Approve') . '"
            data-toggle="modal" data-target="#modal-approve" data-key="' . $paidLeave->request_id . '"><i
                class="fa fa-check-double"></i></a>';
                $cancel = '<a href="#" data-href="' . route('paid-leave.cancel', $paidLeave->id) . '"
            class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Cancel') . '"
            data-toggle="modal" data-target="#modal-cancel"><i class="la la-ban"></i></a>';

                return $view
                    . (Laratrust::isAbleTo('update-leave') && $paidLeave->status == 'Draft' ? $edit : '')
                    . (Laratrust::isAbleTo('update-leave') && $paidLeave->status == 'Draft' ? $submit : '')
                    . (Laratrust::isAbleTo('process-leave') && $paidLeave->status == 'Submitted' ? $process : '')
                    . (Laratrust::isAbleTo('approve-leave') && $paidLeave->status == 'Processed' ? $approve : '')
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
            'date_range' => ['required'],
            'note' => ['string'],
        ]);

        // $email = $request->email;

        // if (User::withoutGlobalScope('active')->whereEmail($email)->exists()) {
        //     return $this->validationError(Lang::get('The email has already been taken.'));
        // }

        DB::beginTransaction();
        try {
            $paidLeave = new PaidLeave;
            $paidLeave->request_id = 'LVE-' . date("ymd") . strtoupper(Str::random(3));
            $paidLeave->by = $request->by;
            $paidLeave->leave_type = $request->leave_type;
            $paidLeave->status = 'Draft';
            $paidLeave->note = $request->note;
            $dateRange = explode(' - ', $request->date_range);
            $paidLeave->start_date = date_create_from_format('d/m/Y', $dateRange[0])->format('Y-m-d');
            $paidLeave->end_date = date_create_from_format('d/m/Y', $dateRange[1])->format('Y-m-d');
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

        $message = Lang::get('Leave request id') . ' \'' . $paidLeave->request_id . '\' '
        . '\' ' . Lang::get('successfully created.');
        return redirect()->route('paid-leave.index')->with('status', $message);
    }

    public function show($id)
    {
        if (!Laratrust::isAbleTo('view-leave')) {
            return abort(404);
        }

        $paidLeave = PaidLeave::select(
            'paid_leave.id',
            'paid_leave.request_id',
            'users.name AS name',
            'leave_type',
            'master_leave_types.name AS type',
            'start_date',
            'end_date',
            'note',
            'status'
        )
            ->leftJoin('users', 'users.id', '=', 'paid_leave.by')
            ->leftJoin('master_leave_types', 'master_leave_types.id', '=', 'paid_leave.leave_type')
            ->where('paid_leave.id', $id)
            ->first();

        $paidLeaveStatus = PaidLeaveStatus::select(
            'paid_leave_status.status',
            'paid_leave_status.at',
            'paid_leave_status.note',
            'users.id AS user_id',
            'users.name AS user_name'
        )
            ->leftJoin('users', 'users.id', '=', 'paid_leave_status.by')
            ->whereLeaveId($id)
            ->orderBy('paid_leave_status.at')
            ->get();

        return view('leave.paid-leave.show', compact('paidLeave', 'paidLeaveStatus'));
    }

    public function edit($id)
    {
        if (!Laratrust::isAbleTo('update-leave')) {
            return abort(404);
        }

        $paidLeave = PaidLeave::select(
            'paid_leave.id',
            'paid_leave.request_id',
            'users.name AS name',
            'leave_type',
            'master_leave_types.name AS type',
            'start_date',
            'end_date',
            'note',
            'status'
        )
            ->leftJoin('users', 'users.id', '=', 'paid_leave.by')
            ->leftJoin('master_leave_types', 'master_leave_types.id', '=', 'paid_leave.leave_type')
            ->where('paid_leave.id', $id)
            ->first();

        $leaveTypes = LeaveType::select('id', 'name')->orderBy('name')->get();

        return view('leave.paid-leave.edit', compact('paidLeave', 'leaveTypes'));
    }

    public function update($id, Request $request)
    {
        if (!Laratrust::isAbleTo('update-leave')) return abort(404);

        $paidLeave = PaidLeave::findOrFail($id);

        if ($paidLeave->status != 'Draft') {
            $message = Lang::get('Leave') . ' \'' . $paidLeave->request_id . '\' ' . Lang::get('cannot be edited.');
            return redirect()->route('paid-leave.index')->withErrors($message);
        }

        $this->validate($request, [
            'leave_type' => ['required', 'integer', 'exists:master_leave_types,id'],
            'date_range' => ['required'],
            'note' => ['string', 'max:225'],
        ]);
        $dateRange = explode(' - ', $request->date_range);
        $start_date = date_create_from_format('d/m/Y', $dateRange[0])->format('Y-m-d');
        $end_date = date_create_from_format('d/m/Y', $dateRange[1])->format('Y-m-d');

       if (PaidLeave::where(function ($query) use ($start_date, $end_date) {
       $query->where('start_date', $start_date)
       ->orWhere('end_date', $end_date);
       })
       ->where('id', '<>', $id)
           ->exists()) {
           return $this->validationError('The requested date is not available for leave as the quota is fully booked.');
           }

        DB::beginTransaction();
        try {
            $paidLeave->leave_type = $request->leave_type;
            $paidLeave->note = $request->note;
            $dateRange = explode(' - ', $request->date_range);
            $paidLeave->start_date = date_create_from_format('d/m/Y', $dateRange[0])->format('Y-m-d');
            $paidLeave->end_date = date_create_from_format('d/m/Y', $dateRange[1])->format('Y-m-d');
            $paidLeave->save();

            $paidLeaveStatus = PaidLeaveStatus::whereLeaveId($id)->whereStatus('Draft')->first();
            $paidLeaveStatus->note = $request->note;
            $paidLeaveStatus->save();
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return abort(500);
        }
        DB::commit();

        $message = Lang::get('Leave') . ' \'' . $paidLeave->request_id . '\' ' . Lang::get('successfully updated.');
        return redirect()->route('paid-leave.index')->with('status', $message);
    }

    public function destroy($id)
    {
        if (!Laratrust::isAbleTo('delete-leave')) return abort(404);

        $paidLeave = PaidLeave::findOrFail($id);

        if ($paidLeave->status != 'Draft') {
            $message = Lang::get('Leave') . ' \'' . $paidLeave->request_id . '\' ' . Lang::get('cannot be deleted.');
            return redirect()->route('paid-leave.index')->withErrors($message);
        }

        $request_id = $paidLeave->request_id;
        $paidLeave->delete();

        $message = Lang::get('Leave') . ' \'' . $request_id . '\' ' . Lang::get('successfully deleted.');
        return redirect()->route('paid-leave.index')->with('status', $message);
    }

    public function submit($id, $type, Request $request)
    {
    if (!Laratrust::isAbleTo('update-leave')) return abort(404);

    $paidLeave = PaidLeave::findOrFail($id);

    if ($paidLeave->status != 'Draft') {
    $message = Lang::get('Leave') . ' \'' . $paidLeave->request_id . '\' ' . Lang::get('cannot be submitted.');
    return redirect()->route('paid-leave.index')->withErrors($message);
    }

    $newStatus = $type == '0' ? 'Draft' : 'Submitted';
    $this->updateLeaveStatus($paidLeave, $newStatus, $request->note, $request);

    $message = Lang::get('Leave') . ' \'' . $paidLeave->request_id . '\' ' . Lang::get('successfully submitted.');
    return redirect()->route('paid-leave.index')->with('status', $message);
    }
    
    public function process($id, $type, Request $request)
    {
    if (!Laratrust::isAbleTo('process-leave')) return abort(404);

    $paidLeave = PaidLeave::findOrFail($id);

    if ($paidLeave->status != 'Submitted') {
    $message = Lang::get('Leave') . ' \'' . $paidLeave->request_id . '\' ' . Lang::get('cannot be processed.');
    return redirect()->route('paid-leave.index')->withErrors($message);
    }

    $newStatus = $type == '0' ? 'Rejected' : 'Processed';
    $this->updateLeaveStatus($paidLeave, $newStatus, $request->note , $request);

    $message = Lang::get('Leave') . ' \'' . $paidLeave->request_id . '\' ' . Lang::get('successfully processed.');
    return redirect()->route('paid-leave.index')->with('status', $message);
    }

    public function approve($id, $type, Request $request)
    {
    if (!Laratrust::isAbleTo('approve-leave')) return abort(404);

    $paidLeave = PaidLeave::findOrFail($id);

    if ($paidLeave->status != 'Processed') {
    $message = Lang::get('Leave') . ' \'' . $paidLeave->request_id . '\' ' . Lang::get('cannot be approved.');
    return redirect()->route('paid-leave.index')->withErrors($message);
    }

    $newStatus = $type == '0' ? 'Rejected' : 'Approved';
    $this->updateLeaveStatus($paidLeave, $newStatus, $request->note , $request);

    $message = Lang::get('Leave') . ' \'' . $paidLeave->request_id . '\' ' . Lang::get('successfully approved.');
    return redirect()->route('paid-leave.index')->with('status', $message);
    }

    public function cancel($id, Request $request)
    {
    if (!Laratrust::isAbleTo('cancel-leave')) return abort(404);

    $paidLeave = PaidLeave::findOrFail($id);

    if ($paidLeave->status != 'Submitted' && $paidLeave->status != 'Process') {
    $message = Lang::get('Leave') . ' \'' . $paidLeave->request_id . '\' ' . Lang::get('cannot be canceled.');
    return redirect()->route('paid-leave.index')->withErrors($message);
    }

    $this->validate($request, [
    'reason' => 'required|string|max:191'
    ]);

    $this->updateLeaveStatus($paidLeave, 'Canceled', $request->reason, $request);

    $message = Lang::get('Leave') . ' \'' . $paidLeave->request_id . '\' ' . Lang::get('successfully canceled.');
    return redirect()->route('paid-leave.index')->with('status', $message);
    }

    private function updateLeaveStatus($paidLeave, $newStatus, $note, $request) {

    DB::beginTransaction();
    try {
    $paidLeave->status = $newStatus;
    $paidLeave->save();

    $paidLeaveStatus = new PaidLeaveStatus;
    $paidLeaveStatus->leave_id = $paidLeave->id;
    $paidLeaveStatus->status = $newStatus;
    $paidLeaveStatus->at = now();
    $paidLeaveStatus->by = Auth::user()->id;
    $paidLeaveStatus->note = $note;
    $paidLeaveStatus->save();
    } catch (Exception $e) {
    DB::rollBack();
    report($e);
    return abort(500);
    }
    DB::commit();

    return;
    }
}
