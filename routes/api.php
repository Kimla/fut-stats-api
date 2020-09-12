<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\WeekendLeagueController;
use App\Http\Controllers\TeamPlayerController;
use App\Http\Controllers\PlayerPriceWatchController;
use App\Http\Controllers\GameController;

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

Route::post('/auth/login', [UserController::class, 'login']);
Route::post('/auth/logout', [UserController::class, 'logout']);
Route::post('/auth/register', [UserController::class, 'register']);

Route::group(['middleware' => 'auth'], function () {
    Route::get('/weekend-leagues', [WeekendLeagueController::class, 'index']);
    Route::post('/weekend-leagues', [WeekendLeagueController::class, 'store']);
    Route::get('/weekend-leagues/{weekendLeague}', [WeekendLeagueController::class, 'get']);
    Route::delete('/weekend-leagues/{weekendLeague}', [WeekendLeagueController::class, 'destroy']);

    Route::get('/team-players',  [TeamPlayerController::class, 'index']);
    Route::post('/team-players',  [TeamPlayerController::class, 'store']);
    Route::post('/team-players/sort-order', [TeamPlayerController::class, 'updateSortOrder']);
    Route::delete('/team-players/{teamPlayer}', [TeamPlayerController::class, 'destroy']);

    Route::get('/player-price-watch', [PlayerPriceWatchController::class, 'index']);
    Route::post('/player-price-watch', [PlayerPriceWatchController::class, 'store']);
    Route::get('/player-price-watch/{playerPriceWatch}', [PlayerPriceWatchController::class, 'update']);
    Route::delete('/player-price-watch/{playerPriceWatch}', [PlayerPriceWatchController::class, 'destroy']);

    Route::get('/games', [GameController::class, 'index']);
    Route::post('/games', [GameController::class, 'store']);
    Route::put('/games/{game}', [GameController::class, 'update']);
    Route::delete('/games/{game}', [GameController::class, 'destroy']);
});
