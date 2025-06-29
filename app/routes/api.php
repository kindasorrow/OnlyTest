<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeAuthController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\TripController;


Route::prefix('auth')->group(function () {
    Route::post('login', [EmployeeAuthController::class, 'login']);
    Route::post('refresh', [EmployeeAuthController::class, 'refresh']);

    Route::middleware('auth:api')->group(function () {
        Route::get('me', [EmployeeAuthController::class, 'me']);
        Route::post('logout', [EmployeeAuthController::class, 'logout']);
    });
});

Route::middleware('auth:api')->group(function () {
    // список доступных авто по тестовому заданию
    Route::get('available-cars', [CarController::class, 'index']);

    // базовый CRUD поездок
    Route::apiResource('trips', TripController::class)
        ->only(['index', 'store', 'update', 'destroy']);
});
