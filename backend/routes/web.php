<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Catch all routes
Route::match(['get', 'post', 'put', 'delete'], '{any?}', function () {
    return response()->json(['detail' => 'Route not found!'], 404);
});
