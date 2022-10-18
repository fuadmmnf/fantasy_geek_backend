<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Database\Seeders\AuthorizationSeeder;
use Database\Seeders\ContestSimulationSeeder;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\LocalMockDataSeeder;
use Database\Seeders\PlayerpositionSeeder;
use Database\Seeders\PointdistributionSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;

class SeederController extends Controller
{
    public function migrateFresh(){
        Artisan::call('migrate:fresh');
        return respoonse()->json("Database migrated", 200);
    }
    public function authSeeder(){
      $authseeder = new AuthorizationSeeder();
      $authseeder->run();
      return respoonse()->json("Authorization Seeder", 200);
    }

    public function playerPositionSeeder(){
        $playerposition = new PlayerpositionSeeder();
        $playerposition->run();
        return respoonse()->json("Player Position Seeder", 200);
    }

    public function pointdistributionSeeder(){
        $pd = new PointdistributionSeeder();
        $pd->run();
        return respoonse()->json("Point Distribution Seeder", 200);
    }
    public function localMockDataSeeder(){
        $mockdata = new LocalMockDataSeeder();
        $mockdata->run();
        return respoonse()->json("Local Mock Data Seeder", 200);
    }
    public function contestSimulationSeeder(){
        $simulation = new ContestSimulationSeeder();
        $simulation->run();
        return respoonse()->json("Contest Simulation Seeder", 200);
    }
}
