<?php

namespace Database\Seeders;

use App\Models\Master\Project;
use App\Models\Master\Skill;
use App\Models\Master\LeaveType;
use App\Models\Master\Shift;
use App\Role;
use Illuminate\Database\Seeder;
use DB;

class MasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Master roles
        $roles = [
            // 'super_administrator',
            'admin',
            'supervisor',
            'team_leader',
            'agent',
        ];

        foreach ($roles as $roleName) {
            $role = new Role();
            $role->name = $roleName;
            $role->display_name = ucwords(str_replace('_', ' ', $roleName));
            $role->save();
        }

        // Master projects
        $projects = [
            'Indosat',
            // 'XL 817',
            // 'AHM',
            // 'Commonwealth BANK',
            // 'ASDP',
            // 'HM Sampoerna',
            // 'Combiphar',
            // 'Bioskop Online',
            // 'LRT',
            // 'Mega Insurance',
            // 'LPDP',
            // 'Sokonindo (DFSK)',
            // 'MRT',
            // 'Ajaib',
            // 'RTS',
            // 'TipTip Tv',
            // 'Haier Sales Indonesia',
            // 'DANA Merchant',
            // 'Tentang Anak',
            // 'Ariston',
            // 'Amar Bank',
            // 'Bukalapak',
            // 'J&T',
            // 'Tokopedia MSQM',
            // 'Indosat Hifi CX',
            // 'Akulaku',
            // 'Hubters',
            // 'Nanovest',
            // 'Pra Kerja',
            // 'OYO Reguler',
            // 'Shipper',
            // 'PAM JAYA',
            // 'MTF Inbound',
            // 'OCBC',
            // 'Seabank',
            // 'Shopee',
            // 'Anter Aja',
        ];
        foreach ($projects as $projectName) {
            $project = new Project();
            $project->name = $projectName;
            $project->save();
        }

        // Master skills
        $skills = ['Inbound VIP', 'Inbound SLI', 'Inbound Postpaid', 'Inbound English', 'Inbound Prepaid HVC', 'Inbound Prepaid MVC', 'Inbound LVC', 'Inbound FMC'];
        foreach ($skills as $skillName) {
            $skill = new Skill();
            $skill->city_id = 3374;
            $skill->project_id = 1;
            $skill->name = $skillName;
            $skill->save();
        }

        // Master leave types
        $leaveTypes = ['Annual Leave', 'Marriage Leave', 'Maternity Leave', 'Grieving Leave'];
        foreach ($leaveTypes as $leaveTypeName) {
            $leaveType = new LeaveType();
            $leaveType->name = $leaveTypeName;
            $leaveType->save();
        }

        // Master shift
        $shifting = [
            'S05',
            'S06',
            'S07',
            'S08',
            'S09',
            'S10',
            'S11',
            'S12',
            'S13',
            'S14',
            'S15',
            'S16',
            'S17',
            'S18',
            'S18b',
            'S22',
            'S23',
            'S05a',
            'S05b',
            'S06a',
            'S06b',
            'S07a',
            'S07b',
            'S08a',
            'S08b',
            'S09a',
            'S09b',
            'S10a',
            'S10b',
            'S11a',
            'S11b',
            'S12a',
            'S12b',
            'S13a',
            'S13b',
            'S14a',
            'S14b',
            'S15a',
            'S15b',
            'S16a',
            'S16b',
            'S17a',
            'S17b',
            'S18a'
        ];

        $start_time = [
            '5:00:00',
            '6:00:00',
            '7:00:00',
            '8:00:00',
            '9:00:00',
            '10:00:00',
            '11:00:00',
            '12:00:00',
            '13:00:00',
            '14:00:00',
            '15:00:00',
            '16:00:00',
            '17:00:00',
            '18:30:00',
            '18:30:00',
            '22:00:00',
            '23:00:00',
            '5:00:00',
            '5:00:00',
            '6:00:00',
            '6:00:00',
            '7:00:00',
            '7:00:00',
            '8:00:00',
            '8:00:00',
            '9:00:00',
            '9:00:00',
            '10:00:00',
            '10:00:00',
            '11:00:00',
            '11:00:00',
            '12:00:00',
            '12:00:00',
            '13:00:00',
            '13:00:00',
            '14:00:00',
            '14:00:00',
            '15:00:00',
            '15:00:00',
            '16:00:00',
            '16:00:00',
            '17:00:00',
            '17:00:00',
            '18:30:00',
        ];

        $end_time = [
            '14:00:00',
            '13:00:00',
            '10:00:00',
            '15:00:00',
            '14:00:00',
            '11:00:00',
            '16:00:00',
            '15:00:00',
            '12:00:00',
            '17:00:00',
            '16:00:00',
            '13:00:00',
            '18:00:00',
            '17:00:00',
            '14:00:00',
            '19:00:00',
            '18:00:00',
            '15:00:00',
            '20:00:00',
            '19:00:00',
            '16:00:00',
            '21:00:00',
            '20:00:00',
            '17:00:00',
            '22:00:00',
            '21:00:00',
            '18:00:00',
            '23:00:00',
            '22:00:00',
            '19:00:00',
            '0:00:00',
            '23:00:00',
            '20:00:00',
            '1:00:00',
            '0:00:00',
            '21:00:00',
            '2:00:00',
            '1:00:00',
            '22:00:00',
            '3:30:00',
            '2:30:00',
            '23:30:00',
            '7:00:00',
            '8:00:00',
        ];

        // Make sure the number of shifts matches the number of start and end times
        if (count($shifting) !== count($start_time) || count($shifting) !== count($end_time)) {
            throw new \Exception('Invalid number of shifts or time slots');
        }

        // Create and save each shift with its corresponding start and end times
        for ($i = 0; $i < count($shifting); $i++) {
            $shift = new Shift();
            $shift->name = $shifting[$i];
            $shift->start_time = $start_time[$i];
            $shift->end_time = $end_time[$i];
            $shift->save();
        }

        // Master activities
        $master_activities = array(
            array(
                "id" => 1,
                "name" => "Training",
                "duration" => "08:00:00",
                "color" => "#24ff24",
                "created_at" => "2023-07-04 09:09:01",
                "updated_at" => "2023-07-11 09:10:51",
                "deleted_at" => null
            ),
            array(
                "id" => 2,
                "name" => "Overtime",
                "duration" => "08:00:00",
                "color" => "#ae0000",
                "created_at" => "2023-07-04 09:09:19",
                "updated_at" => "2023-07-11 09:08:25",
                "deleted_at" => null
            ),
            array(
                "id" => 3,
                "name" => "Coaching",
                "duration" => "00:15:00",
                "color" => "#0000ff",
                "created_at" => "2023-07-04 09:09:45",
                "updated_at" => "2023-07-11 09:09:33",
                "deleted_at" => null
            ),
            array(
                "id" => 4,
                "name" => "Meeting",
                "duration" => "00:15:00",
                "color" => "#ecec00",
                "created_at" => "2023-07-04 09:09:54",
                "updated_at" => "2023-07-11 09:06:29",
                "deleted_at" => null
            ),
            array(
                "id" => 5,
                "name" => "OT Hourly",
                "duration" => "01:00:00",
                "color" => "#ff2828",
                "created_at" => "2023-07-04 09:10:10",
                "updated_at" => "2023-07-11 09:08:50",
                "deleted_at" => null
            ),
            array(
                "id" => 6,
                "name" => "Briefing",
                "duration" => "00:15:00",
                "color" => "#f000f0",
                "created_at" => "2023-07-04 09:11:42",
                "updated_at" => "2023-07-11 09:07:34",
                "deleted_at" => null
            ),
            array(
                "id" => 7,
                "name" => "Training 2 Hours",
                "duration" => "02:00:00",
                "color" => "#00d535",
                "created_at" => "2023-07-04 09:39:40",
                "updated_at" => "2023-07-11 09:02:58",
                "deleted_at" => null
            ),
            array(
                "id" => 8,
                "name" => "Training 4 Hours",
                "duration" => "04:00:00",
                "color" => "#009d00",
                "created_at" => "2023-07-04 09:39:54",
                "updated_at" => "2023-07-11 09:10:35",
                "deleted_at" => null
            ),
            array(
                "id" => 9,
                "name" => "Long Break 1 Hour",
                "duration" => "01:00:00",
                "color" => "#808080",
                "created_at" => "2023-07-04 09:53:56",
                "updated_at" => "2023-07-11 09:04:37",
                "deleted_at" => null
            ),
            array(
                "id" => 10,
                "name" => "Short Break 15 Minutes",
                "duration" => "00:15:00",
                "color" => "#b2b2b2",
                "created_at" => "2023-07-04 09:54:33",
                "updated_at" => "2023-07-11 09:09:54",
                "deleted_at" => null
            ),
            array(
                "id" => 11,
                "name" => "Standby 9 Jam",
                "duration" => "09:00:00",
                "color" => "#ff8000",
                "created_at" => "2023-07-18 09:53:07",
                "updated_at" => "2023-07-18 09:53:07",
                "deleted_at" => null
            ),
            array(
                "id" => 12,
                "name" => "Standby 8 jam",
                "duration" => "08:00:00",
                "color" => "#b75c01",
                "created_at" => "2023-07-18 09:53:50",
                "updated_at" => "2023-07-18 09:55:38",
                "deleted_at" => null
            ),
            array(
                "id" => 13,
                "name" => "Standby 5 Jam",
                "duration" => "05:00:00",
                "color" => "#6a3601",
                "created_at" => "2023-07-18 09:54:23",
                "updated_at" => "2023-07-18 09:55:21",
                "deleted_at" => null
            )
        );

        // Loop through the array and insert data into the 'master_activities' table
        foreach ($master_activities as $activity) {
            DB::table('master_activities')->insert([
                'id' => $activity['id'],
                'name' => $activity['name'],
                'duration' => $activity['duration'],
                'color' => $activity['color'],
                'created_at' => $activity['created_at'],
                'updated_at' => $activity['updated_at'],
                'deleted_at' => $activity['deleted_at'],
            ]);
        }
    }
}