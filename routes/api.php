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
        Route::get('get-location-forecast', [LocationForecastController::class, 'get'])->name('weather.forecast');
        Route::prefix('users')->group(function () {
            Route::get('/locations', [UserController::class, 'getUserLocations']);
            Route::post('/locations', [UserController::class, 'store']);
            Route::delete('/locations/{id}/{date}', [UserController::class, 'destroy']);
        });
    });
});
