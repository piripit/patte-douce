<?php

use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\AvailabilityController;
use App\Http\Controllers\Admin\BoardingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 1. SITE PUBLIC (visible par les clients)
|--------------------------------------------------------------------------
*/
Route::view('/', 'accueil')->name('accueil');
Route::view('/services', 'services')->name('services');
Route::view('/contact', 'contact')->name('contact');
Route::view('/mentions-legales', 'mentions-legales')->name('mentions-legales');

Route::get('/rendez-vous', [BookingController::class, 'create'])->name('rendez-vous');
Route::post('/rendez-vous', [BookingController::class, 'store'])
    ->middleware('throttle:6,1') // 6 envois par minute maximum et par visiteur
    ->name('rendez-vous.store');

/*
|--------------------------------------------------------------------------
| 2. CONNEXION DU PRESTATAIRE
|--------------------------------------------------------------------------
*/
Route::get('/connexion', [AuthController::class, 'showLogin'])->name('login');
Route::post('/connexion', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::post('/deconnexion', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| 3. ESPACE ADMIN (connecté ET administrateur)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', EnsureUserIsAdmin::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::redirect('/', '/admin/demandes');

        Route::get('/demandes', [AdminAppointmentController::class, 'index'])->name('appointments.index');
        Route::patch('/demandes/{appointment}/confirmer', [AdminAppointmentController::class, 'confirm'])->name('appointments.confirm');
        Route::patch('/demandes/{appointment}/refuser', [AdminAppointmentController::class, 'refuse'])->name('appointments.refuse');
        Route::patch('/demandes/{appointment}/annuler', [AdminAppointmentController::class, 'cancel'])->name('appointments.cancel');

        Route::get('/disponibilites', [AvailabilityController::class, 'index'])->name('availabilities.index');
        Route::post('/disponibilites', [AvailabilityController::class, 'store'])->name('availabilities.store');
        Route::patch('/disponibilites/{availability}/basculer', [AvailabilityController::class, 'toggle'])->name('availabilities.toggle');
        Route::delete('/disponibilites/{availability}', [AvailabilityController::class, 'destroy'])->name('availabilities.destroy');

        Route::get('/gardes', [BoardingController::class, 'index'])->name('boarding.index');
        Route::post('/gardes/ouvrir', [BoardingController::class, 'open'])->name('boarding.open');
        Route::post('/gardes/fermer', [BoardingController::class, 'close'])->name('boarding.close');
    });
