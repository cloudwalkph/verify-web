<?php

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
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index');
Route::get('/projects/{projectId}', 'ProjectsController@show');
Route::get('/projects/{projectId}/locations', 'ProjectsController@showLocations');
Route::get('/projects/{projectId}/locations/get-hits', 'ProjectsController@getHits');
Route::get('/projects/{projectId}/locations/{locationId}', 'ProjectLocationsController@show');
Route::post('/projects/{projectId}/locations/{locationId}/faces', 'ProjectLocationsController@faceUpload');
Route::get('/projects/{projectId}/locations/{locationId}/event-reports', 'EventsReportController@show');
Route::get('/projects/{projectId}/locations/{locationId}/audit-reports', 'AuditReportController@show');
Route::get('/projects/{projectId}/locations/{locationId}/gps-reports', 'GpsReportController@show');
Route::get('/projects/{projectId}/locations/{locationId}/audit-reports/preview', 'AuditReportController@preview');
Route::get('/projects/{projectId}/locations/{locationId}/event-reports/preview', 'EventsReportController@preview');
Route::get('/projects/{projectId}/locations/{locationId}/gps-reports/preview', 'GpsReportController@preview');

Route::group(['prefix' => 'management', 'namespace' => 'Management'], function() {
    Route::get('/', 'DashboardController@index');

    Route::group(['prefix' => 'projects'], function () {
        Route::get('/', 'ProjectsController@index');
        Route::get('/create', 'ProjectsController@create');
        Route::post('/create', 'ProjectsController@store');
        Route::get('/update/{id}', 'ProjectsController@edit');
        Route::post('/update/{id}', 'ProjectsController@update');
        Route::post('/update/{id}/locations', 'ProjectsController@createLocations');
    });

    Route::group(['prefix' => 'profile'], function () {
        Route::get('/', 'ProfileController@index');
        Route::post('/', 'ProfileController@update');
    });

    Route::group(['prefix' => 'brands'], function () {
        Route::get('/', 'BrandsController@index');
        Route::post('/', 'BrandsController@store');
        Route::get('/update/{id}', 'BrandsController@edit');
        Route::post('/update/{id}', 'BrandsController@update');
    });

    Route::group(['prefix' => 'user-groups'], function () {
        Route::get('/', 'UserGroupsController@index');
        Route::post('/', 'UserGroupsController@store');
        Route::get('/update/{id}', 'UserGroupsController@edit');
        Route::post('/update/{id}', 'UserGroupsController@update');
    });

    Route::group(['prefix' => 'users'], function () {
        Route::get('/', 'AccountsController@index');
        Route::get('/create', 'AccountsController@create');
        Route::get('/json/', 'AccountsController@getClients');
        Route::post('/', 'AccountsController@store');
        Route::get('/update/{id}', 'AccountsController@edit');
        Route::post('/update/{id}/import-gps', 'AccountsController@importGPSData');
        Route::post('/update/{id}', 'AccountsController@update');
    });
});