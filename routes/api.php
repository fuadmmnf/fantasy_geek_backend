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

Route::prefix('admin')->middleware([])->group(function () { //fix auth permissions later
    Route::get('/fixtures/upcoming', [\App\Http\Controllers\API\FixtureController::class, 'getUpcomingFixturesForAdmin']);
    Route::get('/fixtures/{fixture_id}', [\App\Http\Controllers\API\FixtureController::class, 'getFixtureDetailForTest']);
    Route::post('/fixtures', [\App\Http\Controllers\API\FixtureController::class, 'createFixture']);
});



//users
Route::post('/login', [\App\Http\Controllers\API\UserController::class, 'authorizeUserLogin']);
Route::post('/users/create', [\App\Http\Controllers\API\UserController::class, 'createUser']);
Route::post('/users/register/verify', [\App\Http\Controllers\API\UserController::class, 'verifyUser']);
Route::put('/users/update', [\App\Http\Controllers\API\UserController::class, 'updateUser']);
Route::get('/users/check/{id}', [\App\Http\Controllers\API\UserController::class, 'checkUser']);

// payments
Route::post('/payment/temp', [\App\Http\Controllers\API\PaymentController::class, 'tempPayment']);
Route::get('/payment/fail', [\App\Http\Controllers\API\PaymentController::class, 'paymentFailed']);
Route::get('/payment/cancel', [\App\Http\Controllers\API\PaymentController::class, 'paymentCancel']);
Route::post('/payment/confirm', [\App\Http\Controllers\API\PaymentController::class, 'paymentConfirm']);
Route::get('/payment/list/{user_id}', [\App\Http\Controllers\API\PaymentController::class, 'paymentList']);

//fixtures
Route::get('/fixtures', [\App\Http\Controllers\API\FixtureController::class, 'getFixtures']);
Route::get('/fixtures/{fixture_id}', [\App\Http\Controllers\API\FixtureController::class, 'getSingleFixture'])->where('fixture_id', '[0-9]+');
Route::get('/fixtures/upcoming', [\App\Http\Controllers\API\FixtureController::class, 'getUpcomingFixtures']);
Route::get('/users/{user_id}/fixtures/upcoming', [\App\Http\Controllers\API\FixtureController::class, 'getUpcomingFixturesByUser']);
Route::get('/users/{user_id}/fixtures/running', [\App\Http\Controllers\API\FixtureController::class, 'getRunningFixturesByUser']);
Route::get('/users/{user_id}/fixtures/complete', [\App\Http\Controllers\API\FixtureController::class, 'getCompleteFixturesByUser']);
Route::put('/fixtures', [\App\Http\Controllers\API\FixtureController::class, 'updateFixture']);

//teams
Route::get('/teams/{team_id}', [\App\Http\Controllers\API\TeamController::class, 'getSingleTeam']);
Route::post('/teams', [\App\Http\Controllers\API\TeamController::class, 'createTeam']);

//players
Route::post('/players', [\App\Http\Controllers\API\PlayerController::class, 'createPlayer']);

//contests
Route::get('/test', [\App\Http\Controllers\API\ContestController::class, 'test']);

Route::get('/contests', [\App\Http\Controllers\API\ContestController::class, 'getContestsByFixture']);
Route::get('/contests/{contest_id}', [\App\Http\Controllers\API\ContestController::class, 'getContestDetails'])->where('contest_id', '[0-9]+');
Route::post('/contests', [\App\Http\Controllers\API\ContestController::class, 'createContest']);
Route::put('/contests', [\App\Http\Controllers\API\ContestController::class, 'updateContest']);

//usercontests
Route::get('usercontests/scorecard', [\App\Http\Controllers\API\UsercontestController::class, 'getPlayerScorecard']);
Route::get('usercontests', [\App\Http\Controllers\API\UsercontestController::class, 'getUsercontestsById']);
Route::get('usercontests/ranking', [\App\Http\Controllers\API\UsercontestController::class, 'getUsercontestsRankingById']);
Route::get('/user/{user_id}/fixture/{fixture_id}/usercontests', [\App\Http\Controllers\API\UsercontestController::class, 'getUsercontestsByFixture']);
Route::get('/user/{user_id}/usercontests/upcoming', [\App\Http\Controllers\API\UsercontestController::class, 'getUserUpcomingContests']);
Route::get('/user/{user_id}/usercontests/ongoing', [\App\Http\Controllers\API\UsercontestController::class, 'getUserOngoingContests']);
Route::get('/user/{user_id}/usercontests/completed', [\App\Http\Controllers\API\UsercontestController::class, 'getUserCompletedContests']);
Route::post('/usercontests', [\App\Http\Controllers\API\UsercontestController::class, 'createUsercontest']);

//userfixtureteams
Route::get('/userfixtureteams', [\App\Http\Controllers\API\UserfixtureteamController::class, 'getUserfixtureteamsByFixture']);
Route::post('/userfixtureteams', [\App\Http\Controllers\API\UserfixtureteamController::class, 'createUserfixtureteam']);

