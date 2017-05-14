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
Route::get('/projects/{projectId}/locations/{locationId}', 'ProjectLocationsController@show');
Route::get('/projects/{projectId}/locations/{locationId}/event-reports', 'EventsReportController@show');
Route::get('/projects/{projectId}/locations/{locationId}/audit-reports', 'AuditReportController@show');
Route::get('/projects/{projectId}/locations/{locationId}/audit-reports/preview', 'AuditReportController@preview');
Route::get('/projects/{projectId}/locations/{locationId}/event-reports/preview', 'EventsReportController@preview');

Route::group(['prefix' => 'management', 'namespace' => 'Management'], function() {
    Route::get('/', 'ProjectsController@index');

    Route::group(['prefix' => 'projects'], function () {
        Route::get('/create', 'ProjectsController@create');
        Route::post('/create', 'ProjectsController@store');
        Route::get('/update/{id}', 'ProjectsController@edit');
        Route::post('/update/{id}', 'ProjectsController@update');
    });
});