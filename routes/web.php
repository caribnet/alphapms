<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pms');
});

Route::get('/pms', function () {
    return view('pms');
});
