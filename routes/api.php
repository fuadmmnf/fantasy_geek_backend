<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//users
Route::post('/login', [\App\Http\Controllers\API\UserController::class, 'authorizeUserLogin']);
Route::post('/users/create', [\App\Http\Controllers\API\UserController::class, 'createUser']);
Route::post('/users/register/verify', [\App\Http\Controllers\API\UserController::class, 'verifyUser']);
Route::put('/users', [\App\Http\Controllers\API\UserController::class, 'updateUser']);

//fixtures
Route::get('/fixtures', [\App\Http\Controllers\API\FixtureController::class, 'getFixtures']);
Route::get('/fixtures/{fixture_id}', [\App\Http\Controllers\API\FixtureController::class, 'getSingleFixture'])->where('fixture_id', '[0-9]+');
Route::get('/fixtures/upcoming', [\App\Http\Controllers\API\FixtureController::class, 'getUpcomingFixtures']);
Route::get('/users/{user_id}/fixtures/upcoming', [\App\Http\Controllers\API\FixtureController::class, 'getUpcomingFixturesByUser']);
Route::get('/users/{user_id}/fixtures/running', [\App\Http\Controllers\API\FixtureController::class, 'getRunningFixturesByUser']);
Route::get('/users/{user_id}/fixtures/complete', [\App\Http\Controllers\API\FixtureController::class, 'getCompleteFixturesByUser']);
Route::post('/fixtures', [\App\Http\Controllers\API\FixtureController::class, 'createFixture']);
Route::put('/fixtures', [\App\Http\Controllers\API\FixtureController::class, 'updateFixture']);

//teams
Route::get('/teams/{team_id}', [\App\Http\Controllers\API\TeamController::class, 'getSingleTeam']);
Route::post('/teams', [\App\Http\Controllers\API\TeamController::class, 'createTeam']);

//players
Route::post('/players', [\App\Http\Controllers\API\PlayerController::class, 'createPlayer']);

//contests
Route::get('/contests', [\App\Http\Controllers\API\ContestController::class, 'getContestsByFixture']);
Route::get('/contests/{contest_id}', [\App\Http\Controllers\API\ContestController::class, 'getContestDetails'])->where('contest_id', '[0-9]+');

//usercontests
Route::get('/user/{user_id}/fixture/{fixture_id}/usercontests', [\App\Http\Controllers\API\UsercontestController::class, 'getUsercontestsByFixture']);
Route::get('/user/{user_id}/usercontests/upcoming', [\App\Http\Controllers\API\UsercontestController::class, 'getUserUpcomingContests']);
Route::get('/user/{user_id}/usercontests/ongoing', [\App\Http\Controllers\API\UsercontestController::class, 'getUserOngoingContests']);
Route::get('/user/{user_id}/usercontests/completed', [\App\Http\Controllers\API\UsercontestController::class, 'getUserCompletedContests']);
Route::post('/usercontests', [\App\Http\Controllers\API\UsercontestController::class, 'createUsercontest']);
