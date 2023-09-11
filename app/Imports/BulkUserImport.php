<?php

namespace App\Imports;

use App\Models\Master\City;
use App\Models\Master\Project;
use App\Models\Master\Skill;
use App\User;
use DB;
use Exception;
use Lang, Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;

class BulkUserImport implements ToModel, WithHeadingRow
{
    private $errors = [];

    public function model(array $row)
    {
        $request = request()->all();
        if (isset($row['join_date'])) {
            $dateValue = $row['join_date'];
            $dateObject = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($dateValue);
            $dateString = $dateObject->format('Y-m-d');
        } else {
            $dateValue = null;
            $dateObject = null;
            $dateString = null;
        }

        $email = $row['email'];
        $nik = $row['id'];

        if (User::withoutGlobalScope('active')->whereEmail($email)->exists()) {
            $validator = Validator::make([], []);
            $validator->errors()->add('email', Lang::get('The email has already been used.'));
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        if (User::withoutGlobalScope('active')->whereNik($nik)->exists()) {
            $validator = Validator::make([], []);
            $validator->errors()->add('nik', Lang::get('The ID has already been duplicated.'));
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        if (isset($row['team_leader'])) {
            $teamLeaderName = $row['team_leader'];
            $teamLeaderId = $this->getTeamLeaderId($teamLeaderName);
        } else {
            $teamLeaderId = null;
            $teamLeaderName = null;
        }

        if (isset($row['supervisor'])) {
            $supervisorName = $row['supervisor'];
            $supervisorId = $this->getsupervisorId($supervisorName);
        } else {
            $supervisorId = null;
            $supervisorName = null;
        }

        if (isset($row['site'])) {
            $cityName = $row['site'];
            $cityId = $this->getCityId($cityName);
        } else {
            $cityId = null;
        }
        if (isset($row['project'])) {
            $projectName = $row['project'];
            $projectId = $this->getProjectId($projectName);
        } else {
            $projectId = null;
        }
        if (isset($row['skill'])) {
            $skillName = $row['skill'];
            $skillId = $this->getSkillId($skillName);
        } else {
            $skillId = null;
        }
        if (isset($row['initial_leave'])) {
            $initialLeave = $row['initial_leave'];
        } else {
            $initialLeave = null;
        }
        if (isset($row['used_leave'])) {
            $usedLeave = $row['used_leave'];
        } else {
            $usedLeave = null;
        }

        DB::beginTransaction();
        try {
            $user = new User;
            $user->nik = $row['id'];
            $user->name = Str::title($row['name']);
            $user->email = Str::lower($row['email']);
            $user->password = bcrypt('Pa$$w0rd!');
            $user->gender = $row['gender'];
            $user->religion = $row['religion'];
            $user->join_date = $dateString;
            $user->initial_leave = $initialLeave;
            $user->used_leave = $usedLeave;
            $user->team_leader_id = $teamLeaderId;
            $user->team_leader_name = $teamLeaderName;
            $user->supervisor_id = $supervisorId;
            $user->supervisor_name = $supervisorName;
            $user->city_id = $cityId;
            $user->project_id = $projectId;
            $user->skill_id = $skillId;
            $user->save();

            $user->syncRoles([$request['role']]);
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return abort(500);
        }
        DB::commit();

        // $user->sendEmailVerificationNotification();
    }

    private function getTeamLeaderId($teamLeaderName)
    {
        $teamLeader = User::where('name', $teamLeaderName)->first();

        return $teamLeader ? $teamLeader->nik : null;
    }

    private function getsupervisorId($supervisorName)
    {
        $supervisor = User::where('name', $supervisorName)->first();

        return $supervisor ? $supervisor->nik : null;
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

    public function addError($attribute, $message)
    {
        $this->errors[] = [
            'attribute' => $attribute,
            'message' => $message,
        ];
    }

    public function getErrors()
    {
        return $this->errors;
    }
}