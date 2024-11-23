<?php

use App\Http\Controllers\ComplexController;
use App\Http\Controllers\CourtController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'getDashboard'])->name('dashboard');

    Route::get('/courts', [CourtController::class, 'getCourts'])->name('court.index');
    Route::get('/courts/{court}', [CourtController::class, 'getCourt'])->name('court.show');
    Route::get('/create-courts', [CourtController::class, 'getCreateCourtPage'])->name('get.create.court');
    Route::get('/courts/{court}/reservations', [CourtController::class, 'getCourtReservations'])->name('court.get.reservations');
    
    Route::post('/courts', [CourtController::class, 'store'])->name('court.store');
    Route::put('/courts/{court}', [CourtController::class, 'update'])->name('court.update');
    
    Route::post('/complexes', [ComplexController::class, 'store'])->name('complex.store');
    Route::put('/complexes/{complex}', [ComplexController::class, 'update'])->name('complex.update');
    
    Route::put('/courts/{court}/reservations/{reservation}', [ReservationController::class, 'update']);
    Route::get('/courts/{court}/create-reservations', [ReservationController::class, 'getCreateReservationPage'])->name('get.create.court');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
