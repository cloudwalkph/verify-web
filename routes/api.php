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

    Route::get('me', 'UsersController@profile');
    Route::post('gps', 'UsersController@createLocationRecord');

    Route::get('/ba/events', 'BA\ProjectsController@self');
    // Route::post('/ba/events/{projectId}/locations/{locationId}', 'BA\HitsController@createHit');
    Route::post('/ba/events/locations/{locationId}', 'BA\HitsController@createHit');
    Route::post('/ba/events/locations/{locationId}/auto', 'BA\HitsController@faceUpload');
    Route::post('/ba/hits/{hitId}', 'BA\HitsController@updateImage');

    Route::get('/ba/locations/{locationId}/hits', 'BA\HitsController@getHitsByLocation');

    Route::get('/ba/user-locations/{locationId}', 'BA\UserLocationController@self');
    Route::post('/ba/user-locations/{locationId}', 'BA\UserLocationController@saveLocation');

    Route::get('/raw-videos', 'ProjectVideosController@getVideos');
    Route::put('/raw-videos/{videoId}/process', 'ProjectVideosController@processVideo');
    Route::put('/raw-videos/{videoId}/complete', 'ProjectVideosController@completeVideoProcessing');
    Route::post('/raw-videos/{videoId}/results', 'ProjectVideosController@createVideoResult');
});

Route::post('v1/locations/{locationId}', 'API\BA\HitsController@createHit');