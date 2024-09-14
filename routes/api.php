<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\ReservationController;
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

Route::resource('/user', UserController::class)
    ->middleware('auth:sanctum')
    ->only(['show', 'update']);

Route::post('/register', [RegisterController::class, 'store']);
Route::post('/login', [AuthController::class, 'store']);
Route::post('/logout', [AuthController::class, 'destroy'])->middleware('auth:sanctum');


Route::resource('/car', CarController::class)->only(['index', 'show']);

Route::resource('/reservation', ReservationController::class)
    ->only(['store', 'index'])->middleware('auth:sanctum');
Route::post('/reservation/get-price',[ ReservationController::class, 'getPrice']);