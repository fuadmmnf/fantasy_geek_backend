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
Route::post('/users', [\App\Http\Controllers\API\UserController::class, 'createUser']);
Route::post('/users/register/verify', [\App\Http\Controllers\API\UserController::class, 'verifyUser']);
Route::put('/users', [\App\Http\Controllers\API\UserController::class, 'updateUser']);

//matches
Route::post('/matches', [\App\Http\Controllers\API\MatchController::class, 'createMatch']);
