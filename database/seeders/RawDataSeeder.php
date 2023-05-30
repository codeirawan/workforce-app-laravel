<?php

namespace Database\Seeders;

use App\Models\Forecast\RawData;
use Illuminate\Database\Seeder;

class RawDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $startDate = '2023-01-01';
        $endDate = '2023-04-30';

        $startTime = strtotime('00:00:00');
        $endTime = strtotime('23:00:00');

        $cityIds = [3171, 3471]; // Array of city IDs

        for ($i = 1; $i <= 30000; $i++) {
            $randomDate = date('Y-m-d', mt_rand(strtotime($startDate), strtotime($endDate)));
            $randomHour = mt_rand(0, 23);

            $startTime = sprintf('%02d:00:00', $randomHour);
            $endTime = sprintf('%02d:00:00', ($randomHour + 1) % 24);

            $rawData = new RawData();
            $rawData->date = $randomDate;
            $rawData->start_time = $randomDate . ' ' . $startTime;
            $rawData->end_time = $randomDate . ' ' . $endTime;
            $rawData->volume = mt_rand(0, 150);
            $rawData->city_id = $cityIds[array_rand($cityIds)]; // Assign a random city ID from the array
            $rawData->project_id = mt_rand(1, 2);
            $rawData->skill_id = mt_rand(1, 2);
            $rawData->save();
        }
    }
}
