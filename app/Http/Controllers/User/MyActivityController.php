<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User\UserActivity;
use App\Http\Controllers\Controller;
use App\Models\User\UserActivityDetail;
use DataTables, Lang, Auth, DB, Exception;

class MyActivityController extends Controller
{
    public function index()
    {
        return view('user.my-activity.index');
    }

    public function data()
    {
        $userActivities = UserActivity::select('id', 'created_at')->whereUserId(Auth::user()->id);

        return DataTables::of($userActivities)
            ->addColumn('action', function($userActivity) {
                $view = '<a href="' . route('my-activity.show', $userActivity->id) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('View') . '"><i class="la la-eye"></i></a>';
                $edit = '<a href="' . route('my-activity.edit', $userActivity->id) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Edit') . '"><i class="la la-edit"></i></a>';
                $delete = '<a href="#" data-href="' . route('my-activity.destroy', $userActivity->id) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="' . Lang::get('Delete') . '" data-toggle="modal" data-target="#modal-delete" data-key="' . $userActivity->created_at . '"><i class="la la-trash"></i></a>';

                return $view . ($userActivity->created_at->gt(today()) ? $edit . $delete : '');
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        return view('user.my-activity.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'aktivitas' => ['required'],
            'aktivitas.*.aktivitas' => ['required', 'string', 'max:255'],
            'aktivitas.*.target' => ['required', 'string', 'max:255'],
        ]);
        dd($request);
        if (UserActivity::whereUserId(Auth::user()->id)->whereDate('created_at', today())->exists())
            return $this->validationError(Lang::get('You have posted activities for today.'));

        DB::beginTransaction();
        try {
            $userActivity = new UserActivity;
            $userActivity->user_id = Auth::user()->id;
            $userActivity->save();

            foreach ($request->input('aktivitas', []) as $activity) {
                $userActivityDetail = new UserActivityDetail;
                $userActivityDetail->user_activity_id = $userActivity->id;
                $userActivityDetail->activity = $activity['aktivitas'];
                $userActivityDetail->target = $activity['target'];
                $userActivityDetail->is_completed = isset($activity['selesai']) ? 1 : 0;
                $userActivityDetail->save();
            }
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return abort(500);
        }
        DB::commit();

        $message = Lang::get('Activity') . ' \'' . $userActivity->created_at . '\' ' . Lang::get('successfully created.');
        return redirect()->route('my-activity.index')->with('status', $message);
    }
}
