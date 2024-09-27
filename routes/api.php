<?php

use App\Http\Controllers\Api\User\LocationForecastController;
use App\Http\Controllers\Api\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/user/locations', [UserController::class, 'getUserLocations'])->middleware('auth:sanctum');

Route::post('/forecast', [LocationForecastController::class, 'store'])->middleware('auth:sanctum');
