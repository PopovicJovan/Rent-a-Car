<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


// Routes for user
Route::resource('/user', UserController::class)
    ->middleware(['auth:sanctum'])
    ->only(['show', 'update']);

// Auth routes
Route::post('/register', [RegisterController::class, 'store']);
Route::post('/login', [AuthController::class, 'store']);
Route::post('/logout', [AuthController::class, 'destroy'])->middleware('auth:sanctum');

// Car routes
Route::resource('/car', CarController::class)->only(['index', 'show']);
Route::post('/car/{car}/is-available', [CarController::class, 'isCarAvailable']);

// Reservation routes
Route::resource('/reservation', ReservationController::class)
    ->only(['store', 'index', 'destroy'])->middleware('auth:sanctum');
Route::post('/reservation/{car}/get-price',[ ReservationController::class, 'getPrice']);

// Rate routes
Route::post('/reservation/{reservation}', [RateController::class, 'store'])->middleware('auth:sanctum');

// Admin Routes (with is-admin middleware)
Route::group(['middleware' => ['auth:sanctum', 'is-admin']], function() {

    // Admin User Routes
    Route::resource('/user', Admin\UserController::class)->only(['index', 'destroy']);
    Route::get('/user/{user}/reservation', [Admin\ReservationController::class, 'getReservationsForUser']);

    // Admin Car Routes
    Route::resource('/car', Admin\CarController::class)->only(['store', 'destroy']);
    Route::post('/car/{car}', [Admin\CarController::class, 'update']);

    // Admin Reservation Routes
    Route::get('/reservation-all', [Admin\ReservationController::class, 'index']);
});