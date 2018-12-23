<?php

Auth::routes();

Route::get('/', function () {
    return view('index');
});

Route::get('/launch', function () {
    return view('launch');
});
