<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/clear', [\App\Http\Controllers\API\SeederController::class, 'clear'])->name('clear');
Route::get('/migratebyadmin', [\App\Http\Controllers\API\SeederController::class, 'migrate'])->name('migrate');
Route::get('/migrate', [\App\Http\Controllers\API\SeederController::class, 'migrateFresh']);

Route::get('/seed/auth', [\App\Http\Controllers\API\SeederController::class, 'authSeeder']);
Route::get('/seed/playerposition', [\App\Http\Controllers\API\SeederController::class, 'playerPositionSeeder']);
Route::get('/seed/pointdistribution', [\App\Http\Controllers\API\SeederController::class, 'pointdistributionSeeder']);
Route::get('/seed/localmock', [\App\Http\Controllers\API\SeederController::class, 'localMockDataSeeder']);
Route::get('/seed/contest/simulation', [\App\Http\Controllers\API\SeederController::class, 'contestSimulationSeeder']);
Route::get('/scheduler/fixture_state_check', [\App\Http\Controllers\API\SeederController::class, 'fixtureStateCheck']);
Route::get('/scheduler/running_fixture_tracker', [\App\Http\Controllers\API\SeederController::class, 'runningFixtureTracker']);

