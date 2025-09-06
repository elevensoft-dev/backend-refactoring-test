<?php

declare(strict_types=1);

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
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

Route::controller(AccountController::class)
    ->prefix('account')
    ->group(function () {
        Route::post('register', 'register');

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('profile', 'profile');
            Route::put('profile', 'update');
            Route::put('password', 'changePassword');
        });

        Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
        Route::post('reset-password', [ForgotPasswordController::class, 'reset']);
    });

Route::controller(AuthController::class)
    ->prefix('auth')
    ->group(function () {
        Route::post('login', 'login');
        Route::middleware('auth:sanctum')->post('logout', 'logout');
    });

Route::middleware('auth:sanctum')->apiResource('users', UserController::class);
