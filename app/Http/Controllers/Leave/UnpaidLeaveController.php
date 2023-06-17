<?php

namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Leave\UnpaidLeave;
use App\Models\Leave\UnpaidLeaveStatus;
use DataTables;
use DB, Auth;
use Exception;
use Lang;
use Laratrust;
use Str;

class UnpaidLeaveController extends Controller
{
    public function index()
    {
        if (!Laratrust::isAbleTo('view-leave')) {
            return abort(404);
        }

        return view('leave.unpaid-leave.index');
    }

    public function data()
    {
        if (!Laratrust::isAbleTo('view-leave')) {
            return abort(404);
        }

        $userId = Auth::user()->id;
        $unpaidLeaves = UnpaidLeave::select(
            'unpaid_leave.id',
            'request_id',
            'users.name AS name',
            'start_date',
            'end_date',
            'note',
            'status',
            'unpaid_leave.created_at'
        )
            ->leftJoin('users', 'users.id', '=', 'unpaid_leave.by')
            ->where('by', $userId)
            ->get();

        return DataTables::of($unpaidLeaves)
            ->editColumn('status', function ($unpaidLeave) {
                $color = $unpaidLeave->status == 'Draft' ? 'light'
                    : ($unpaidLeave->status == 'Submitted' ? 'warning'
                        : ($unpaidLeave->status == 'Processed' ? 'primary'
                            : ($unpaidLeave->status == 'Approved' ? 'success'
                                : ($unpaidLeave->status == 'Rejected' ? 'danger'
                                    : ($unpaidLeave->status == 'Canceled' ? 'dark'
                                        : 'secondary')))));

                return '<span class="badge badge-' . $color . '">' . Lang::get($unpaidLeave->status) . '</span>';
            })
            ->addColumn('action', function ($unpaidLeave) {
                $view = '<a href="' . route('unpaid-leave.show', $unpaidLeave->id) . '"
            class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('View') . '"><i
                class="la la-eye"></i></a>';
                $edit = '<a href="' . route('unpaid-leave.edit', $unpaidLeave->id) . '"
            class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Edit') . '"><i
                class="la la-edit"></i></a>';
                $delete = '<a href="#" data-href="' . route('unpaid-leave.destroy', $unpaidLeave->id) . '"
            class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Delete') . '"
            data-toggle="modal" data-target="#modal-delete" data-key="' . $unpaidLeave->request_id . '"><i
                class="la la-trash"></i></a>';
                $submit = '<a href="#" data-id="' . $unpaidLeave->id . '"
            class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Submit') . '"
            data-toggle="modal" data-target="#modal-submit" data-key="' . $unpaidLeave->request_id . '"><i
                class="fa-regular fa-paper-plane"></i></a>';
                $process = '<a href="#" data-id="' . $unpaidLeave->id . '"
            class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Process') . '"
            data-toggle="modal" data-target="#modal-process" data-key="' . $unpaidLeave->request_id . '"><i
                class="fa fa-check"></i></a>';
                $approve = '<a href="#" data-id="' . $unpaidLeave->id . '"
            class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Approve') . '"
            data-toggle="modal" data-target="#modal-approve" data-key="' . $unpaidLeave->request_id . '"><i
                class="fa fa-check-double"></i></a>';
                $cancel = '<a href="#" data-href="' . route('unpaid-leave.cancel', $unpaidLeave->id) . '"
            class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Cancel') . '"
            data-toggle="modal" data-target="#modal-cancel"><i class="la la-ban"></i></a>';

                return $view
                    . (Laratrust::isAbleTo('update-leave') && $unpaidLeave->status == 'Draft' ? $edit : '')
                    . (Laratrust::isAbleTo('update-leave') && $unpaidLeave->status == 'Draft' ? $submit : '')
                    . (Laratrust::isAbleTo('process-leave') && $unpaidLeave->status == 'Submitted' ? $process : '')
                    . (Laratrust::isAbleTo('approve-leave') && $unpaidLeave->status == 'Processed' ? $approve : '')
                    . (Laratrust::isAbleTo('cancel-leave') && ($unpaidLeave->status == 'Submitted' || $unpaidLeave->status == 'Process') ? $cancel : '')
                    . (Laratrust::isAbleTo('delete-leave') && $unpaidLeave->status == 'Draft' ? $delete : '');
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function create()
    {
        if (!Laratrust::isAbleTo('create-leave')) {
            return abort(404);
        }

        return view('leave.unpaid-leave.create');
    }

    public function store(Request $request)
    {
        if (!Laratrust::isAbleTo('create-leave')) {
            return abort(404);
        }

        $this->validate($request, [
            'by' => ['required', 'integer'],
            'date_range' => ['required'],
            'note' => ['required', 'string', 'max:191'],
        ]);

        DB::beginTransaction();
        try {
            $unpaidLeave = new UnpaidLeave;
            $unpaidLeave->request_id = 'OFF-' . date("ymd") . strtoupper(Str::random(3));
            $unpaidLeave->by = $request->by;
            $unpaidLeave->status = 'Draft';
            $unpaidLeave->note = $request->note;
            $dateRange = explode(' - ', $request->date_range);
            $unpaidLeave->start_date = date_create_from_format('d/m/Y', $dateRange[0])->format('Y-m-d');
            $unpaidLeave->end_date = date_create_from_format('d/m/Y', $dateRange[1])->format('Y-m-d');
            $unpaidLeave->save();


            $unpaidLeaveStatus = new UnpaidLeaveStatus;
            $unpaidLeaveStatus->leave_id = $unpaidLeave->id;
            $unpaidLeaveStatus->status = 'Draft';
            $unpaidLeaveStatus->at = now();
            $unpaidLeaveStatus->by = Auth::user()->id;
            $unpaidLeaveStatus->note = $request->note;
            $unpaidLeaveStatus->save();
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return abort(500);
        }
        DB::commit();

        $message = Lang::get('Time off request id') . ' \'' . $unpaidLeave->request_id . '\' '
            . '\' ' . Lang::get('successfully created.');
        return redirect()->route('unpaid-leave.index')->with('status', $message);
    }

    public function show($id)
    {
        if (!Laratrust::isAbleTo('view-leave')) {
            return abort(404);
        }

        $unpaidLeave = UnpaidLeave::select(
            'unpaid_leave.id',
            'unpaid_leave.request_id',
            'users.name AS name',
            'start_date',
            'end_date',
            'note',
            'status'
        )
            ->leftJoin('users', 'users.id', '=', 'unpaid_leave.by')
            ->where('unpaid_leave.id', $id)
            ->first();

        $unpaidLeaveStatus = UnpaidLeaveStatus::select(
            'unpaid_leave_status.status',
            'unpaid_leave_status.at',
            'unpaid_leave_status.note',
            'users.id AS user_id',
            'users.name AS user_name'
        )
            ->leftJoin('users', 'users.id', '=', 'unpaid_leave_status.by')
            ->whereLeaveId($id)
            ->orderBy('unpaid_leave_status.at')
            ->get();

        return view('leave.unpaid-leave.show', compact('unpaidLeave', 'unpaidLeaveStatus'));
    }

    public function edit($id)
    {
        if (!Laratrust::isAbleTo('update-leave')) {
            return abort(404);
        }

        $unpaidLeave = UnpaidLeave::select(
            'unpaid_leave.id',
            'unpaid_leave.request_id',
            'users.name AS name',
            'start_date',
            'end_date',
            'note',
            'status'
        )
            ->leftJoin('users', 'users.id', '=', 'unpaid_leave.by')
            ->where('unpaid_leave.id', $id)
            ->first();

        return view('leave.unpaid-leave.edit', compact('unpaidLeave'));
    }

    public function update($id, Request $request)
    {
        if (!Laratrust::isAbleTo('update-leave')) return abort(404);

        $unpaidLeave = UnpaidLeave::findOrFail($id);

        if ($unpaidLeave->status != 'Draft') {
            $message = Lang::get('Time off') . ' \'' . $unpaidLeave->request_id . '\' ' . Lang::get('cannot be edited.');
            return redirect()->route('unpaid-leave.index')->withErrors($message);
        }

        $this->validate($request, [
            'date_range' => ['required'],
            'note' => ['required', 'string', 'max:191'],
        ]);
        $dateRange = explode(' - ', $request->date_range);
        $start_date = date_create_from_format('d/m/Y', $dateRange[0])->format('Y-m-d');
        $end_date = date_create_from_format('d/m/Y', $dateRange[1])->format('Y-m-d');

        if (UnpaidLeave::where(function ($query) use ($start_date, $end_date) {
            $query->where('start_date', $start_date)
                ->orWhere('end_date', $end_date);
        })
            ->where('id', '<>', $id)
            ->exists()
        ) {
            return $this->validationError('The requested date is not available for leave as the quota is fully booked.');
        }

        DB::beginTransaction();
        try {
            $unpaidLeave->note = $request->note;
            $dateRange = explode(' - ', $request->date_range);
            $unpaidLeave->start_date = date_create_from_format('d/m/Y', $dateRange[0])->format('Y-m-d');
            $unpaidLeave->end_date = date_create_from_format('d/m/Y', $dateRange[1])->format('Y-m-d');
            $unpaidLeave->save();

            $unpaidLeaveStatus = UnpaidLeaveStatus::whereLeaveId($id)->whereStatus('Draft')->first();
            $unpaidLeaveStatus->note = $request->note;
            $unpaidLeaveStatus->save();
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return abort(500);
        }
        DB::commit();

        $message = Lang::get('Time off') . ' \'' . $unpaidLeave->request_id . '\' ' . Lang::get('successfully
        updated.');
        return redirect()->route('unpaid-leave.index')->with('status', $message);
    }

    public function destroy($id)
    {
        if (!Laratrust::isAbleTo('delete-leave')) return abort(404);

        $unpaidLeave = UnpaidLeave::findOrFail($id);

        if ($unpaidLeave->status != 'Draft') {
            $message = Lang::get('Time off') . ' \'' . $unpaidLeave->request_id . '\' ' . Lang::get('cannot be
            deleted.');
            return redirect()->route('unpaid-leave.index')->withErrors($message);
        }

        $request_id = $unpaidLeave->request_id;
        $unpaidLeave->delete();

        $message = Lang::get('Time off') . ' \'' . $request_id . '\' ' . Lang::get('successfully deleted.');
        return redirect()->route('unpaid-leave.index')->with('status', $message);
    }

    public function submit($id, $type, Request $request)
    {
        if (!Laratrust::isAbleTo('update-leave')) return abort(404);

        $unpaidLeave = UnpaidLeave::findOrFail($id);

        if ($unpaidLeave->status != 'Draft') {
            $message = Lang::get('Time off') . ' \'' . $unpaidLeave->request_id . '\' ' . Lang::get('cannot be submitted.');
            return redirect()->route('unpaid-leave.index')->withErrors($message);
        }

        $newStatus = $type == '0' ? 'Draft' : 'Submitted';
        $this->updateLeaveStatus($unpaidLeave, $newStatus, $request->note, $request);

        $message = Lang::get('Time off') . ' \'' . $unpaidLeave->request_id . '\' ' . Lang::get('successfully submitted.');
        return redirect()->route('unpaid-leave.index')->with('status', $message);
    }

    public function process($id, $type, Request $request)
    {
        if (!Laratrust::isAbleTo('process-leave')) return abort(404);

        $unpaidLeave = UnpaidLeave::findOrFail($id);

        if ($unpaidLeave->status != 'Submitted') {
            $message = Lang::get('Time off') . ' \'' . $unpaidLeave->request_id . '\' ' . Lang::get('cannot be processed.');
            return redirect()->route('unpaid-leave.index')->withErrors($message);
        }

        $newStatus = $type == '0' ? 'Rejected' : 'Processed';
        $this->updateLeaveStatus($unpaidLeave, $newStatus, $request->note, $request);

        $message = Lang::get('Time off') . ' \'' . $unpaidLeave->request_id . '\' ' . Lang::get('successfully processed.');
        return redirect()->route('unpaid-leave.index')->with('status', $message);
    }

    public function approve($id, $type, Request $request)
    {
        if (!Laratrust::isAbleTo('approve-leave')) return abort(404);

        $unpaidLeave = UnpaidLeave::findOrFail($id);

        if ($unpaidLeave->status != 'Processed') {
            $message = Lang::get('Time off') . ' \'' . $unpaidLeave->request_id . '\' ' . Lang::get('cannot be approved.');
            return redirect()->route('unpaid-leave.index')->withErrors($message);
        }

        $newStatus = $type == '0' ? 'Rejected' : 'Approved';
        $this->updateLeaveStatus($unpaidLeave, $newStatus, $request->note, $request);

        $message = Lang::get('Time off') . ' \'' . $unpaidLeave->request_id . '\' ' . Lang::get('successfully approved.');
        return redirect()->route('unpaid-leave.index')->with('status', $message);
    }

    public function cancel($id, Request $request)
    {
        if (!Laratrust::isAbleTo('cancel-leave')) return abort(404);

        $unpaidLeave = UnpaidLeave::findOrFail($id);

        if ($unpaidLeave->status != 'Submitted' && $unpaidLeave->status != 'Process') {
            $message = Lang::get('Time off') . ' \'' . $unpaidLeave->request_id . '\' ' . Lang::get('cannot be canceled.');
            return redirect()->route('unpaid-leave.index')->withErrors($message);
        }

        $this->validate($request, [
            'reason' => 'required|string|max:191'
        ]);

        $this->updateLeaveStatus($unpaidLeave, 'Canceled', $request->reason, $request);

        $message = Lang::get('Time off') . ' \'' . $unpaidLeave->request_id . '\' ' . Lang::get('successfully canceled.');
        return redirect()->route('unpaid-leave.index')->with('status', $message);
    }

    private function updateLeaveStatus($unpaidLeave, $newStatus, $note)
    {

        DB::beginTransaction();
        try {
            $unpaidLeave->status = $newStatus;
            $unpaidLeave->save();

            $unpaidLeaveStatus = new UnpaidLeaveStatus;
            $unpaidLeaveStatus->leave_id = $unpaidLeave->id;
            $unpaidLeaveStatus->status = $newStatus;
            $unpaidLeaveStatus->at = now();
            $unpaidLeaveStatus->by = Auth::user()->id;
            $unpaidLeaveStatus->note = $note;
            $unpaidLeaveStatus->save();
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return abort(500);
        }
        DB::commit();

        return;
    }
}
