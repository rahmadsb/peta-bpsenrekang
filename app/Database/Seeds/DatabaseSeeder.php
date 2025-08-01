<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Database\Seeds\UserSeeder;
use App\Database\Seeds\OpsiKegiatanSeeder;
use App\Database\Seeds\DesaSeeder;
use App\Database\Seeds\SlsSeeder;
use App\Database\Seeds\BlokSensusSeeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // add all seeders here
        $this->call(UserSeeder::class);
        $this->call(OpsiKegiatanSeeder::class);
        $this->call(DesaSeeder::class);
        $this->call(SlsSeeder::class);
        $this->call(BlokSensusSeeder::class);
    }
}
