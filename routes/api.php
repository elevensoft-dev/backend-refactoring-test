<?php

use App\Http\Controllers\Auth\AuthenticateController;
use App\Http\Controllers\UserController;
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

Route::apiResource('users', UserController::class);

Route::controller(AuthenticateController::class)->group(function () {

    Route::post('/login', [AuthenticateController::class, 'attempt'])->name('login');
    Route::post('/register', [AuthenticateController::class, 'register'])->name('register');
    Route::post('/logout', [AuthenticateController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('/refreshToken', [AuthenticateController::class, 'refreshToken'])->middleware('auth:sanctum');
});
