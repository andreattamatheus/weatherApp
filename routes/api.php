<?php

use App\Http\Controllers\Api\User\LocationForecastController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('get-location-forecast', [LocationForecastController::class, 'get']);
        Route::prefix('users')->group(function () {
            Route::get('', [UserController::class, 'index']);
            Route::get('/locations', [UserController::class, 'getUserLocations']);
            Route::post('/locations', [LocationForecastController::class, 'store']);
            Route::delete('/locations/{id}', [LocationForecastController::class, 'destroy']);
        });
    });
});
