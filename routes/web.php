<?php

use App\Http\Controllers\ComplexController;
use App\Http\Controllers\CourtController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SectionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('complex.index');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'getDashboard'])->name('dashboard');

    Route::get('/courts', [CourtController::class, 'getCourts'])->name('court.index');
    Route::get('/courts/{court}', [CourtController::class, 'getCourt'])->name('court.show');
    Route::get('/create-courts', [CourtController::class, 'getCreateCourtPage'])->name('get.create.court');
    Route::get('/courts/{court}/reservations', [CourtController::class, 'getCourtReservations'])->name('court.get.reservations');

    Route::get('/reservations/{reservation}', [ReservationController::class, 'get'])->name('get.reservation');
    Route::put('/reservations/{reservation}/update-is-no-show-status', [ReservationController::class, 'updateIsNoShowStatus']);
    Route::put('/reservations/{reservation}/update-is-canceled-status', [ReservationController::class, 'updateIsCanceledStatus']);
    Route::get('/reservations/{reservation}/invoice', [ReservationController::class, 'getInvoice'])->name('reservation.invoice');

    Route::put('/reservations/{reservation}/invoices/{invoice}/update-status', [ReservationController::class, 'updateInvoiceStatus']);
    Route::get('/reservations/{reservation}/invoices/{invoice}/generate-pdf', [ReservationController::class, 'generatePdf']);


    
    Route::post('/courts', [CourtController::class, 'store'])->name('court.store');
    Route::put('/courts/{court}', [CourtController::class, 'update'])->name('court.update');
    
    Route::get('/complexes', [ComplexController::class, 'get'])->name('complex.index');
    Route::get('/complexes/{id}', [ComplexController::class, 'show'])->name('complexes.show');
    Route::get('/complexes/{complex}/courts', [ComplexController::class, 'getComplexCourts'])->name('complex.courts.index');
    Route::get('/create-complexes', [ComplexController::class, 'getCreateComplexPage'])->name('get.create.complex');
    Route::post('/complexes', [ComplexController::class, 'store'])->name('complex.store');
    Route::put('/complexes/{complex}', [ComplexController::class, 'update'])->name('complex.update');
    
    Route::put('/courts/{court}/reservations/{reservation}', [ReservationController::class, 'update']);
    Route::post('/courts/{court}/reservations', [ReservationController::class, 'store'])->name('reservation.store');
    Route::get('/courts/{court}/create-reservations', [ReservationController::class, 'getCreateReservationPage'])->name('get.create.court.reservation');

    Route::post('/courts/{court}/sections', [SectionController::class, 'store'])->name('section.store');
    Route::get('/courts/{court}/sections', [SectionController::class, 'get'])->name('get.sections');

    Route::post('/customer', [CustomerController::class, 'store'])->name('customer.store');
    Route::get('/customers', [CustomerController::class, 'get'])->name('get.customers');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
