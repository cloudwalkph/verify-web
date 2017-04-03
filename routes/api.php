<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1',
    'middleware' => 'auth:api',
    'namespace' => 'API'], function() {

    Route::get('/ba/events', 'BA\ProjectsController@self');
    Route::post('/ba/events/{projectId}/locations/{locationId}', 'BA\HitsController@createHit');
    Route::put('/ba/hits/{hitId}', 'BA\HitsController@updateImage');

    Route::get('/ba/locations/{locationId}/hits', 'BA\HitsController@getHitsByLocation');
});