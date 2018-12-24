<?php

Auth::routes();

Route::get('/', function () {
    return view('index');
});

Route::get('/launch', 'LaunchRequestController@index');

Route::get('/launch/create', "LaunchRequestController@create");

Route::post('/launch', 'LaunchRequestController@store');

Route::get('/launch/{launchRequest}', 'LaunchRequestController@show');