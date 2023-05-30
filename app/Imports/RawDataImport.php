<?php

namespace App\Imports;

use App\Models\Forecast\RawData;
use App\Models\Master\City;
use App\Models\Master\Project;
use App\Models\Master\Skill;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Str;

class RawDataImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    public function __construct()
    {
        $importDate = date('ymd');
        $uniqueId = strtoupper(Str::random(3));

        $this->batchId = "{$importDate}-{$uniqueId}";

    }
    public function model(array $row)
    {
        HeadingRowFormatter::default('none');

        // Extract the date value from the Excel file
        $dateValue = $row['date'];
        $dateObject = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($dateValue);
        $dateString = $dateObject->format('Y-m-d');

        // Convert the time values to PHP DateTime objects
        $startTime = $row['start_time'];
        $endTime = $row['end_time'];

        $startDateTime = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($startTime)->format('Y-m-d H:i:s');
        $endDateTime = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($endTime)->format('Y-m-d H:i:s');

        // Retrieve the city_id, project_id, and skill_id based on their names
        $cityName = $row['site'];
        $cityId = $this->getCityId($cityName);
        $projectName = $row['project'];
        $projectId = $this->getProjectId($projectName);
        $skillName = $row['skill'];
        $skillId = $this->getSkillId($skillName);

        $rawData = new RawData([
            'batch_id' => $this->batchId,
            'date' => $dateString,
            'start_time' => $startDateTime,
            'end_time' => $endDateTime,
            'volume' => $row['volume'],
            'city_id' => $cityId,
            'project_id' => $projectId,
            'skill_id' => $skillId,
        ]);

        return $rawData;

    }

    private function getCityId($cityName)
    {
        $city = City::where('name', $cityName)->first();

        return $city ? $city->id : null;
    }
    private function getProjectId($projectName)
    {
        $project = Project::where('name', $projectName)->first();

        return $project ? $project->id : null;
    }
    private function getSkillId($skillName)
    {
        $skill = Skill::where('name', $skillName)->first();

        return $skill ? $skill->id : null;
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
