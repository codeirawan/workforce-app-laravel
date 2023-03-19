<?php

namespace App\Http\Controllers\Forecast;

use App\Http\Controllers\Controller;
use App\Models\Forecast\RawData;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laratrust;
use Yajra\DataTables\Facades\DataTables;

class WeeklyForecastController extends Controller
{
    public function index()
    {
        if (!Laratrust::isAbleTo('view-forecast')) {
            return abort(404);
        }

        return view('forecast.weekly.index');
    }

    public function data()
    {
        if (!Laratrust::isAbleTo('view-forecast')) {
            return abort(404);
        }

        $weeklyForecast = RawData::select(
            DB::raw('IF(WEEK(DATE_SUB(date, INTERVAL (WEEKDAY(date) + 7) % 7 DAY), 3) = 1 AND DAYOFYEAR(DATE_SUB(date, INTERVAL (WEEKDAY(date) + 7) % 7 DAY)) <= 3, 52, WEEK(DATE_SUB(date, INTERVAL (WEEKDAY(date) + 7) % 7 DAY), 3)) as week'),
            DB::raw('DATE_SUB(MIN(date), INTERVAL (WEEKDAY(MIN(date)) + 7) % 7 DAY) as start_date'),
            DB::raw('DATE_ADD(MAX(date), INTERVAL 6 - (WEEKDAY(MAX(date)) + 7) % 7 DAY) as end_date'),

            DB::raw('SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00) as sum_per_week'),
            DB::raw('SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00) / 7 as avg_per_day'),

            DB::raw('SUM(CASE WHEN DAYOFWEEK(date) = 1 THEN 00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00 ELSE 0 END) as sum_sun'),
            DB::raw('SUM(CASE WHEN DAYOFWEEK(date) = 2 THEN 00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00 ELSE 0 END) as sum_mon'),
            DB::raw('SUM(CASE WHEN DAYOFWEEK(date) = 3 THEN 00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00 ELSE 0 END) as sum_tue'),
            DB::raw('SUM(CASE WHEN DAYOFWEEK(date) = 4 THEN 00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00 ELSE 0 END) as sum_wed'),
            DB::raw('SUM(CASE WHEN DAYOFWEEK(date) = 5 THEN 00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00 ELSE 0 END) as sum_thu'),
            DB::raw('SUM(CASE WHEN DAYOFWEEK(date) = 6 THEN 00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00 ELSE 0 END) as sum_fri'),
            DB::raw('SUM(CASE WHEN DAYOFWEEK(date) = 7 THEN 00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00 ELSE 0 END) as sum_sat'),

            DB::raw('SUM(CASE WHEN DAYOFWEEK(date) = 1 THEN 00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00 ELSE 0 END)/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00) *100 as pct_sun'),
            DB::raw('SUM(CASE WHEN DAYOFWEEK(date) = 2 THEN 00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00 ELSE 0 END)/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00) *100 as pct_mon'),
            DB::raw('SUM(CASE WHEN DAYOFWEEK(date) = 3 THEN 00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00 ELSE 0 END)/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00) *100 as pct_tue'),
            DB::raw('SUM(CASE WHEN DAYOFWEEK(date) = 4 THEN 00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00 ELSE 0 END)/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00) *100 as pct_wed'),
            DB::raw('SUM(CASE WHEN DAYOFWEEK(date) = 5 THEN 00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00 ELSE 0 END)/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00) *100 as pct_thu'),
            DB::raw('SUM(CASE WHEN DAYOFWEEK(date) = 6 THEN 00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00 ELSE 0 END)/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00) *100 as pct_fri'),
            DB::raw('SUM(CASE WHEN DAYOFWEEK(date) = 7 THEN 00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00 ELSE 0 END)/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00) *100 as pct_sat'),

        )
            ->groupBy(DB::raw('DATE_FORMAT(DATE_SUB(date, INTERVAL (WEEKDAY(date) + 7) % 7 DAY), "%Y-%m"), week'))
            ->orderBy(DB::raw('DATE_FORMAT(DATE_SUB(date, INTERVAL (WEEKDAY(date) + 7) % 7 DAY), "%Y-%m"), week'))
            ->get();

        return DataTables::of($weeklyForecast)
            ->addColumn('start_date', function ($row) {
                $dateTime = new DateTime($row->start_date);
                return $dateTime->format('D d M Y');
            })
            ->addColumn('end_date', function ($row) {
                $dateTime = new DateTime($row->end_date);
                return $dateTime->format('D d M Y');
            })
            ->addColumn('avg_per_day', function ($row) {
                return round($row->avg_per_day, 2);
            })
            ->addColumn('pct_mon', function ($row) {
                return round($row->pct_mon, 2);
            })
            ->addColumn('pct_tue', function ($row) {
                return round($row->pct_tue, 2);
            })
            ->addColumn('pct_wed', function ($row) {
                return round($row->pct_wed, 2);
            })
            ->addColumn('pct_thu', function ($row) {
                return round($row->pct_thu, 2);
            })
            ->addColumn('pct_fri', function ($row) {
                return round($row->pct_fri, 2);
            })
            ->addColumn('pct_sat', function ($row) {
                return round($row->pct_sat, 2);
            })
            ->addColumn('pct_sun', function ($row) {
                return round($row->pct_sun, 2);
            })
            ->make(true);

    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
