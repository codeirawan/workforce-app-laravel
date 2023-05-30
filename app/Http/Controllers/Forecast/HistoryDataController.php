<?php

namespace App\Http\Controllers\Forecast;

use App\Http\Controllers\Controller;
use DateTime;
use DB;
use Laratrust;
use Yajra\DataTables\Facades\DataTables;

class HistoryDataController extends Controller
{
    public function daily()
    {
        if (!Laratrust::isAbleTo('view-forecast')) {
            return abort(404);
        }

        return view('forecast.history.daily.index');
    }

    public function dailyData()
    {
        if (!Laratrust::isAbleTo('view-forecast')) {
            return abort(404);
        }

        $dailyData = DB::select('CALL forecast.sum_history_per_day()');

        return DataTables::of($dailyData)
            ->addColumn('date', function ($row) {
                $dateTime = new DateTime($row->date);
                return $dateTime->format('D, d M Y');
            })
            ->make(true);

    }

    public function weekly()
    {
        if (!Laratrust::isAbleTo('view-forecast')) {
            return abort(404);
        }

        return view('forecast.history.weekly.index');
    }

    public function weeklyData()
    {
        if (!Laratrust::isAbleTo('view-forecast')) {
            return abort(404);
        }

        $weeklyData = DB::select('CALL forecast.sum_history_per_week()');

        return DataTables::of($weeklyData)
            ->make(true);

    }
}
