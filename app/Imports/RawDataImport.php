<?php

namespace App\Imports;

use App\Models\Forecast\RawData;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RawDataImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $dateValue = $row['date'];
        $dateObject = \DateTime::createFromFormat('Y-m-d', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($dateValue)->format('Y-m-d'));
        $dateString = $dateObject->format('Y-m-d');
        return new RawData([
            'date' => $dateString,
            '00_01' => $row['0000_0100'],
            '01_02' => $row['0100_0200'],
            '02_03' => $row['0200_0300'],
            '03_04' => $row['0300_0400'],
            '04_05' => $row['0400_0500'],
            '05_06' => $row['0500_0600'],
            '06_07' => $row['0600_0700'],
            '07_08' => $row['0700_0800'],
            '08_09' => $row['0800_0900'],
            '09_10' => $row['0900_1000'],
            '10_11' => $row['1000_1100'],
            '11_12' => $row['1100_1200'],
            '12_13' => $row['1200_1300'],
            '13_14' => $row['1300_1400'],
            '14_15' => $row['1400_1500'],
            '15_16' => $row['1500_1600'],
            '16_17' => $row['1600_1700'],
            '17_18' => $row['1700_1800'],
            '18_19' => $row['1800_1900'],
            '19_20' => $row['1900_2000'],
            '20_21' => $row['2000_2100'],
            '21_22' => $row['2100_2200'],
            '22_23' => $row['2200_2300'],
            '23_00' => $row['2300_0000'],
        ]);

    }
}
