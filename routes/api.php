<?php

use App\Http\Controllers\API\Auth\AuthenticationController;
use App\Http\Controllers\API\CourtController;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\ReservationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/login', [AuthenticationController::class, 'login']);


Route::middleware('auth:sanctum')->group(function() {
    Route::get('/customer', function(Request $request) {
        return $request->user();
    });
    Route::get('/courts', [CourtController::class, 'index']);
    // Route::get('/reservations', [ReservationController::class, 'getCustomerReservations']);
    Route::post('/verify-otp', [AuthenticationController::class, 'verifyOtp']);
 
    Route::prefix('/courts')->group(function () {
        Route::get('/{court}', [CourtController::class, 'show']);
        Route::get('/{court}/available-reservations', [CourtController::class, 'getAvailableReservations']);
    });

    Route::prefix('/reservations')->group(function () {
        Route::get('/', [ReservationController::class, 'index']);
        Route::get('/{reservation}', [ReservationController::class, 'show']);
        Route::post('/{court}', [ReservationController::class, 'store']);
    });

    Route::prefix('/customers')->group(function () {
        Route::get('/{reservation}', [CustomerController::class, 'show']);
    });
});
