<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
<<<<<<< HEAD
use App\Schedulers\FixtureStateCheckerScheduler;
use App\Schedulers\RunningFixtureTrackerScheduler;
=======
>>>>>>> master
use Database\Seeders\AuthorizationSeeder;
use Database\Seeders\ContestSimulationSeeder;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\LocalMockDataSeeder;
use Database\Seeders\PlayerpositionSeeder;
use Database\Seeders\PointdistributionSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
<<<<<<< HEAD
use Illuminate\Support\Facades\Session;

class SeederController extends Controller
{
    // clear configs, routes and serve
    public function clear()
    {
//        $user = Auth::user();
//        if (! ($user !== null && $user->can('admin-menu'))) {
//            return 'error';
//        }
        // Artisan::call('route:cache');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('key:generate');
        Artisan::call('optimize');
        Session::flush();
        return 'Config and Route Cached. All Cache Cleared';
    }

    public function migrate()
    {
//        $user = Auth::user();
//        if ($user !== null && $user->can('admin-menu')) {
            // Artisan::call('route:cache');
            Artisan::call('migrate', array('--force' => true));
            return 'Migration done';
//        }
//        return 'error';
    }


=======

class SeederController extends Controller
{
>>>>>>> master
    public function migrateFresh(){
        Artisan::call('migrate:fresh');
        return response()->json("Database migrated", 200);
    }
<<<<<<< HEAD
=======

    public function seed(){
        Artisan::call('db:seed');
        return response()->json("Seeded defaults", 200);
    }
>>>>>>> master
    public function authSeeder(){
      $authseeder = new AuthorizationSeeder();
      $authseeder->run();
      return response()->json("Authorization Seeder", 200);
    }

    public function playerPositionSeeder(){
        $playerposition = new PlayerpositionSeeder();
        $playerposition->run();
        return response()->json("Player Position Seeder", 200);
    }

    public function pointdistributionSeeder(){
        $pd = new PointdistributionSeeder();
        $pd->run();
        return response()->json("Point Distribution Seeder", 200);
    }
    public function localMockDataSeeder(){
        $mockdata = new LocalMockDataSeeder();
        $mockdata->run();
        return response()->json("Local Mock Data Seeder", 200);
    }
    public function contestSimulationSeeder(){
        $simulation = new ContestSimulationSeeder();
        $simulation->run();
        return response()->json("Contest Simulation Seeder", 200);
    }
<<<<<<< HEAD

    public function fixtureStateCheck(){
        (new FixtureStateCheckerScheduler())();
        return response()->json("Finished executing fixture status checker", 200);
    }

    public function runningFixtureTracker(){
        (new RunningFixtureTrackerScheduler())();
        return response()->json("Finished executing fixture stat tracker", 200);
    }
=======
>>>>>>> master
}
