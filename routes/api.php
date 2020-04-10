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

Route::post('/auth/login', 'UserController@login');
Route::post('/auth/logout', 'UserController@logout');
Route::post('/auth/register', 'UserController@register');

Route::get('/games', 'GameController@index');
Route::post('/games', 'GameController@store');
Route::put('/games/{game}', 'GameController@update');
