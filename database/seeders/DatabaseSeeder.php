<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Artisan::call('migrate:fresh');
        $this->call(AuthorizationSeeder::class);
        $this->call(PlayerpositionSeeder::class);
        $this->call(PointdistributionSeeder::class);

<<<<<<< HEAD
        $this->call(ContestSimulationSeeder::class);
=======
//        $this->call(ContestSimulationSeeder::class);
>>>>>>> master
//        $this->call(LocalMockDataSeeder::class);
    }
}
