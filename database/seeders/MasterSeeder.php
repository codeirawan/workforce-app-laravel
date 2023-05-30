<?php

namespace Database\Seeders;

use App\Models\Master\Project;
use App\Models\Master\Skill;
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
        // Create project
        $projects = [
            'Indosat',
            'XL 817',
            'AHM',
            'Commonwealth BANK',
            'ASDP',
            'HM Sampoerna',
            'Combiphar',
            'Bioskop Online',
            'LRT',
            'Mega Insurance',
            'LPDP',
            'Sokonindo (DFSK)',
            'MRT',
            'Ajaib',
            'RTS',
            'TipTip Tv',
            'Haier Sales Indonesia',
            'DANA Merchant',
            'Tentang Anak',
            'Ariston',
            'Amar Bank',
            'Bukalapak',
            'J&T',
            'Tokopedia MSQM',
            'Indosat Hifi CX',
            'Akulaku',
            'Hubters',
            'Nanovest',
            'Pra Kerja',
            'OYO Reguler',
            'Shipper',
            'PAM JAYA',
            'MTF Inbound',
            'OCBC',
            'Seabank',
            'Shopee',
            'Anter Aja',
        ];
        foreach ($projects as $projectName) {
            $project = new Project();
            $project->name = $projectName;
            $project->save();
        }

        // Create skill
        $skills = ['Inbound', 'Outbound', 'Email', 'Chat', 'Social Media'];
        foreach ($skills as $skillName) {
            $skill = new Skill();
            $skill->name = $skillName;
            $skill->save();
        }

    }
}
