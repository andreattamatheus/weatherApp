<?php

use App\Http\Controllers\Api\User\LocationForecastController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);

Route::get('users', [UserController::class, 'index']);
Route::get('users/locations', [UserController::class, 'getUserLocations']);
Route::post('users/locations', [LocationForecastController::class, 'store']);
Route::delete('locations/{id}', [LocationForecastController::class, 'destroy']);
