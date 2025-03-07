<?php

use App\Http\Controllers\API\Auth\RegisterCustomerController;
use App\Http\Controllers\API\CourtController;
use App\Http\Controllers\API\ReservationController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegisterCustomerController::class, 'register']);


Route::middleware('auth:sanctum')->group(function() {
 
    Route::prefix('/courts')->group(function () {
        Route::get('/', [CourtController::class, 'index']);
        Route::get('/{court}', [CourtController::class, 'show']);
    });

    Route::prefix('/reservations')->group(function () {
        Route::get('/', [ReservationController::class, 'index']);
        Route::get('/{reservation}', [ReservationController::class, 'show']);
    });

});
