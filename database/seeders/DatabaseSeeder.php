<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call(ProvinceSeeder::class);
        $this->call(CitySeeder::class);
        $this->call(LaratrustSeeder::class);
        $this->call(MasterSeeder::class);
        // $this->call(RawDataSeeder::class);

    }
}
