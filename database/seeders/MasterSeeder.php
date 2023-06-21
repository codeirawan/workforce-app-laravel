<?php

namespace Database\Seeders;

use App\Models\Master\Project;
use App\Models\Master\Skill;
use App\Models\Master\LeaveType;
use App\Models\Master\Shift;
use App\Role;
use Illuminate\Database\Seeder;

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
            $skill->name = $skillName;
            $skill->save();
        }

        // Master leave types
        $leaveTypes = ['Annual Leave', 'Marriage Leave', 'Maternity Leave'];
        foreach ($leaveTypes as $leaveTypeName) {
            $leaveType = new LeaveType();
            $leaveType->name = $leaveTypeName;
            $leaveType->save();
        }

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
            'S23'
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
            '18:00:00',
            '18:30:00',
            '22:00:00',
            '23:00:00'
        ];

        $end_time = [
            '14:00:00',
            '15:00:00',
            '16:00:00',
            '17:00:00',
            '18:00:00',
            '19:00:00',
            '20:00:00',
            '21:00:00',
            '22:00:00',
            '23:00:00',
            '00:00:00',
            '01:00:00',
            '02:00:00',
            '03:00:00',
            '03:30:00',
            '07:00:00',
            '08:00:00'
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
    }
}
