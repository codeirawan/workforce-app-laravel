<?php

namespace App\Http\Controllers\Forecast;

use App\Http\Controllers\Controller;
use App\Models\Forecast\RawData;
use DateTime;
use DB;
use Illuminate\Http\Request;
use Laratrust;
use Yajra\DataTables\Facades\DataTables;

class DailyForecastController extends Controller
{
    public function index()
    {
        if (!Laratrust::isAbleTo('view-forecast')) {
            return abort(404);
        }

        return view('forecast.daily.index');
    }

    public function data()
    {
        if (!Laratrust::isAbleTo('view-forecast')) {
            return abort(404);
        }

        $dailyForecast = RawData::select(
            'date',
            DB::raw('SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00) as sum_per_day'),
            DB::raw('SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00) / 24 as avg_per_day'),

            DB::raw('(00_01/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00))*100 as pct_00_01'),
            DB::raw('(01_02/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00))*100 as pct_01_02'),
            DB::raw('(02_03/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00))*100 as pct_02_03'),
            DB::raw('(03_04/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00))*100 as pct_03_04'),
            DB::raw('(04_05/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00))*100 as pct_04_05'),
            DB::raw('(05_06/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00))*100 as pct_05_06'),

            DB::raw('(06_07/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00))*100 as pct_06_07'),
            DB::raw('(07_08/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00))*100 as pct_07_08'),
            DB::raw('(08_09/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00))*100 as pct_08_09'),
            DB::raw('(09_10/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00))*100 as pct_09_10'),
            DB::raw('(10_11/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00))*100 as pct_10_11'),
            DB::raw('(11_12/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00))*100 as pct_11_12'),

            DB::raw('(12_13/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00))*100 as pct_12_13'),
            DB::raw('(13_14/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00))*100 as pct_13_14'),
            DB::raw('(14_15/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00))*100 as pct_14_15'),
            DB::raw('(15_16/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00))*100 as pct_15_16'),
            DB::raw('(16_17/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00))*100 as pct_16_17'),
            DB::raw('(17_18/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00))*100 as pct_17_18'),

            DB::raw('(18_19/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00))*100 as pct_18_19'),
            DB::raw('(19_20/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00))*100 as pct_19_20'),
            DB::raw('(20_21/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00))*100 as pct_20_21'),
            DB::raw('(21_22/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00))*100 as pct_21_22'),
            DB::raw('(22_23/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00))*100 as pct_22_23'),
            DB::raw('(23_00/SUM(00_01 + 01_02 + 02_03 + 03_04 + 04_05 + 05_06 + 06_07 + 07_08 + 08_09 + 09_10 + 10_11 + 11_12 + 12_13 + 13_14 + 14_15 + 15_16 + 16_17 + 17_18 + 18_19 + 19_20 + 20_21 + 21_22 + 22_23 + 23_00))*100 as pct_23_00'),
        )
            ->groupBy('date', '00_01', '01_02', '02_03', '03_04', '04_05', '05_06', '06_07', '07_08', '08_09', '09_10', '10_11', '11_12', '12_13', '13_14', '14_15', '15_16', '16_17', '17_18', '18_19', '19_20', '20_21', '21_22', '22_23', '23_00')
            ->get();

        return DataTables::of($dailyForecast)
            ->addColumn('day', function ($row) {
                $dateTime = new DateTime($row->date);
                return $dateTime->format('D');
            })
            ->addColumn('date', function ($row) {
                $dateTime = new DateTime($row->date);
                return $dateTime->format('d M Y');
            })
            ->addColumn('avg_per_day', function ($row) {
                return round($row->avg_per_day, 2);
            })
            ->addColumn('pct_00_01', function ($row) {
                return round($row->pct_00_01, 2) . '%';
            })
            ->addColumn('pct_01_02', function ($row) {
                return round($row->pct_01_02, 2) . '%';
            })
            ->addColumn('pct_02_03', function ($row) {
                return round($row->pct_02_03, 2) . '%';
            })
            ->addColumn('pct_03_04', function ($row) {
                return round($row->pct_03_04, 2) . '%';
            })
            ->addColumn('pct_04_05', function ($row) {
                return round($row->pct_04_05, 2) . '%';
            })
            ->addColumn('pct_05_06', function ($row) {
                return round($row->pct_05_06, 2) . '%';
            })
            ->addColumn('pct_06_07', function ($row) {
                return round($row->pct_06_07, 2) . '%';
            })
            ->addColumn('pct_07_08', function ($row) {
                return round($row->pct_07_08, 2) . '%';
            })
            ->addColumn('pct_08_09', function ($row) {
                return round($row->pct_08_09, 2) . '%';
            })
            ->addColumn('pct_09_10', function ($row) {
                return round($row->pct_09_10, 2) . '%';
            })
            ->addColumn('pct_10_11', function ($row) {
                return round($row->pct_10_11, 2) . '%';
            })
            ->addColumn('pct_11_12', function ($row) {
                return round($row->pct_11_12, 2) . '%';
            })
            ->addColumn('pct_12_13', function ($row) {
                return round($row->pct_12_13, 2) . '%';
            })
            ->addColumn('pct_13_14', function ($row) {
                return round($row->pct_13_14, 2) . '%';
            })
            ->addColumn('pct_14_15', function ($row) {
                return round($row->pct_14_15, 2) . '%';
            })
            ->addColumn('pct_15_16', function ($row) {
                return round($row->pct_15_16, 2) . '%';
            })
            ->addColumn('pct_16_17', function ($row) {
                return round($row->pct_16_17, 2) . '%';
            })
            ->addColumn('pct_17_18', function ($row) {
                return round($row->pct_17_18, 2) . '%';
            })
            ->addColumn('pct_18_19', function ($row) {
                return round($row->pct_18_19, 2) . '%';
            })
            ->addColumn('pct_19_20', function ($row) {
                return round($row->pct_19_20, 2) . '%';
            })
            ->addColumn('pct_20_21', function ($row) {
                return round($row->pct_20_21, 2) . '%';
            })
            ->addColumn('pct_21_22', function ($row) {
                return round($row->pct_21_22, 2) . '%';
            })
            ->addColumn('pct_22_23', function ($row) {
                return round($row->pct_22_23, 2) . '%';
            })
            ->addColumn('pct_23_00', function ($row) {
                return round($row->pct_23_00, 2) . '%';
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
