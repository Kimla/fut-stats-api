<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TeamPlayerController;
use App\Http\Controllers\WeekendLeagueController;
use Illuminate\Http\Response;
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

Route::get('/sanctum/csrf-cookie', function () {
    return new Response('', 204);
});

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout']);
Route::post('/auth/register', [AuthController::class, 'register']);

Route::group(['middleware' => 'auth'], function () {
    Route::get('/auth/user', [AuthController::class, 'user']);

    Route::get('/players', [PlayerController::class, 'index']);
    Route::post('/players', [PlayerController::class, 'store']);
    Route::put('/players/{player}', [PlayerController::class, 'update']);
    Route::delete('/players/{player}', [PlayerController::class, 'destroy']);

    Route::get('/teams', [TeamController::class, 'index']);
    Route::post('/teams', [TeamController::class, 'store']);
    Route::put('/teams/{team}', [TeamController::class, 'update']);
    Route::delete('/teams/{team}', [TeamController::class, 'destroy']);

    Route::get('/weekend-leagues', [WeekendLeagueController::class, 'index']);
    Route::post('/weekend-leagues', [WeekendLeagueController::class, 'store']);
    Route::get('/weekend-leagues/{weekendLeague}', [WeekendLeagueController::class, 'get']);
    Route::delete('/weekend-leagues/{weekendLeague}', [WeekendLeagueController::class, 'destroy']);

    Route::get('/team-players', [TeamPlayerController::class, 'index']);
    Route::post('/team-players', [TeamPlayerController::class, 'store']);
    Route::post('/team-players/sort-order', [TeamPlayerController::class, 'updateSortOrder']);
    Route::delete('/team-players/{teamPlayer}', [TeamPlayerController::class, 'destroy']);

    Route::get('/games', [GameController::class, 'index']);
    Route::post('/games', [GameController::class, 'store']);
    Route::put('/games/{game}', [GameController::class, 'update']);
    Route::delete('/games/{game}', [GameController::class, 'destroy']);
});
