<?php

namespace App\Http\Controllers;

use App\Models\Schedule\ScheduleShift;
use Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $today = date('Y-m-d'); // Get the current date in 'Y-m-d' format

        // Calculate the start date (Monday) and end date (Sunday) of the current week
        $startOfWeek = date('Y-m-d', strtotime('last Monday', strtotime($today)));
        $endOfWeek = date('Y-m-d', strtotime('next Sunday', strtotime($today)));

        // dd($today, $startOfWeek, $endOfWeek);

        $getSchedules = ScheduleShift::select(
            'users.nik',
            'users.name',
            'users.gender',
            'users.religion',
            'master_shift.name AS shift',
            'schedule_shift.date',
            'schedule_shift.start_time',
            'schedule_shift.end_time',
            'schedule_shift.id AS schedule_shift',
            'schedule_period.id AS schedule_period',
            'schedule_period.publish',
        )
            ->leftJoin('users', 'users.id', '=', 'schedule_shift.agent_id')
            ->leftJoin('master_shift', 'master_shift.id', '=', 'schedule_shift.shift_id')
            ->leftJoin('schedule_period', 'schedule_period.id', '=', 'schedule_shift.schedule_id')
            ->leftJoin('params', 'params.id', '=', 'schedule_period.forecast_id')
            ->groupBy(
                'users.nik',
                'users.name',
                'users.gender',
                'users.religion',
                'master_shift.name',
                'schedule_shift.date',
                'schedule_shift.start_time',
                'schedule_shift.end_time',
                'schedule_shift.id',
                'schedule_period.id',
                'schedule_period.publish',
            )
            ->where(function ($query) {
                $query->where('params.skill_id', Auth::user()->skill_id)
                    ->orWhere(function ($subquery) {
                        $subquery->where('users.supervisor_id', '=', Auth::user()->nik);
                    })
                    ->orWhere(function ($subquery) {
                        $subquery->where('users.team_leader_id', '=', Auth::user()->nik);
                    });
            })
            ->whereDate('params.start_date', '>=', $startOfWeek)
            ->whereDate('params.end_date', '<=', $endOfWeek)
            ->orderBy('schedule_shift.date')
            ->orderBy('schedule_shift.start_time')
            ->get();

        return view('dashboard', compact('getSchedules'));
    }
}
