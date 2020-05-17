<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Response;

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

Route::get( '/sanctum/csrf-cookie',  function () {
    return new Response('', 204);
});

Route::post('/auth/login', 'UserController@login');
Route::post('/auth/logout', 'UserController@logout');
Route::post('/auth/register', 'UserController@register');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/weekend-leagues', 'WeekendLeagueController@index');
    Route::post('/weekend-leagues', 'WeekendLeagueController@store');
    Route::get('/weekend-leagues/{weekendLeague}', 'WeekendLeagueController@get');

    Route::get('/player-price-watch', 'PlayerPriceWatchController@index');
    Route::post('/player-price-watch', 'PlayerPriceWatchController@store');
    Route::get('/player-price-watch/{playerPriceWatch}', 'PlayerPriceWatchController@update');
    Route::delete('/player-price-watch/{playerPriceWatch}', 'PlayerPriceWatchController@destroy');

    Route::get('/games', 'GameController@index');
    Route::post('/games', 'GameController@store');
    Route::put('/games/{game}', 'GameController@update');
    Route::delete('/games/{game}', 'GameController@destroy');
});
