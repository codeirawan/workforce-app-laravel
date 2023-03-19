<?php

namespace App\Http\Controllers\Forecast;

use App\Http\Controllers\Controller;
use App\Models\Forecast\RawData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laratrust;
use Yajra\DataTables\Facades\DataTables;

class MonthlyForecastController extends Controller
{
    public function index()
    {
        if (!Laratrust::isAbleTo('view-forecast')) {
            return abort(404);
        }

        return view('forecast.monthly.index');
    }

    public function data()
    {
        if (!Laratrust::isAbleTo('view-forecast')) {
            return abort(404);
        }

        $monthlyForecast = RawData::select(
            DB::raw('YEAR(date) as year'),
            DB::raw('SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00) as sum_per_year'),
            DB::raw('SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00) / 12 as avg_per_month'),

            DB::raw('SUM(CASE WHEN MONTH(date) = 1 THEN 00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00 ELSE 0 END) as sum_jan'),
            DB::raw('SUM(CASE WHEN MONTH(date) = 2 THEN 00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00 ELSE 0 END) as sum_feb'),
            DB::raw('SUM(CASE WHEN MONTH(date) = 3 THEN 00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00 ELSE 0 END) as sum_mar'),
            DB::raw('SUM(CASE WHEN MONTH(date) = 4 THEN 00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00 ELSE 0 END) as sum_apr'),
            DB::raw('SUM(CASE WHEN MONTH(date) = 5 THEN 00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00 ELSE 0 END) as sum_may'),
            DB::raw('SUM(CASE WHEN MONTH(date) = 6 THEN 00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00 ELSE 0 END) as sum_jun'),
            DB::raw('SUM(CASE WHEN MONTH(date) = 7 THEN 00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00 ELSE 0 END) as sum_jul'),
            DB::raw('SUM(CASE WHEN MONTH(date) = 8 THEN 00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00 ELSE 0 END) as sum_aug'),
            DB::raw('SUM(CASE WHEN MONTH(date) = 9 THEN 00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00 ELSE 0 END) as sum_sep'),
            DB::raw('SUM(CASE WHEN MONTH(date) = 10 THEN 00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00 ELSE 0 END) as sum_oct'),
            DB::raw('SUM(CASE WHEN MONTH(date) = 11 THEN 00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00 ELSE 0 END) as sum_nov'),
            DB::raw('SUM(CASE WHEN MONTH(date) = 12 THEN 00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00 ELSE 0 END) as sum_dec'),

            DB::raw('SUM(CASE WHEN MONTH(date) = 1 THEN 00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00 ELSE 0 END)/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00) *100 as pct_jan'),
            DB::raw('SUM(CASE WHEN MONTH(date) = 2 THEN 00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00 ELSE 0 END)/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00) *100 as pct_feb'),
            DB::raw('SUM(CASE WHEN MONTH(date) = 3 THEN 00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00 ELSE 0 END)/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00) *100 as pct_mar'),
            DB::raw('SUM(CASE WHEN MONTH(date) = 4 THEN 00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00 ELSE 0 END)/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00) *100 as pct_apr'),
            DB::raw('SUM(CASE WHEN MONTH(date) = 5 THEN 00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00 ELSE 0 END)/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00) *100 as pct_may'),
            DB::raw('SUM(CASE WHEN MONTH(date) = 6 THEN 00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00 ELSE 0 END)/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00) *100 as pct_jun'),
            DB::raw('SUM(CASE WHEN MONTH(date) = 7 THEN 00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00 ELSE 0 END)/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00) *100 as pct_jul'),
            DB::raw('SUM(CASE WHEN MONTH(date) = 8 THEN 00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00 ELSE 0 END)/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00) *100 as pct_aug'),
            DB::raw('SUM(CASE WHEN MONTH(date) = 9 THEN 00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00 ELSE 0 END)/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00) *100 as pct_sep'),
            DB::raw('SUM(CASE WHEN MONTH(date) = 10 THEN 00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00 ELSE 0 END)/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00) *100 as pct_oct'),
            DB::raw('SUM(CASE WHEN MONTH(date) = 11 THEN 00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00 ELSE 0 END)/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00) *100 as pct_nov'),
            DB::raw('SUM(CASE WHEN MONTH(date) = 12 THEN 00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00 ELSE 0 END)/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00) *100 as pct_dec')
        )
            ->groupBy('year')
            ->get();

        return DataTables::of($monthlyForecast)
            ->addColumn('avg_per_month', function ($row) {
                return round($row->avg_per_month, 2);
            })
            ->addColumn('pct_jan', function ($row) {
                return round($row->pct_jan, 2);
            })
            ->addColumn('pct_feb', function ($row) {
                return round($row->pct_feb, 2);
            })
            ->addColumn('pct_mar', function ($row) {
                return round($row->pct_mar, 2);
            })
            ->addColumn('pct_apr', function ($row) {
                return round($row->pct_apr, 2);
            })
            ->addColumn('pct_may', function ($row) {
                return round($row->pct_may, 2);
            })
            ->addColumn('pct_jun', function ($row) {
                return round($row->pct_jun, 2);
            })
            ->addColumn('pct_jul', function ($row) {
                return round($row->pct_jul, 2);
            })
            ->addColumn('pct_aug', function ($row) {
                return round($row->pct_aug, 2);
            })
            ->addColumn('pct_sep', function ($row) {
                return round($row->pct_sep, 2);
            })
            ->addColumn('pct_oct', function ($row) {
                return round($row->pct_oct, 2);
            })
            ->addColumn('pct_nov', function ($row) {
                return round($row->pct_nov, 2);
            })
            ->addColumn('pct_dec', function ($row) {
                return round($row->pct_dec, 2);
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
