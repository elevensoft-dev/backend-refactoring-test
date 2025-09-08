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
    ->name('account.')
    ->group(function () {
        Route::post('register', 'register')->name('register');

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('profile', 'profile')->name('profile');
            Route::put('profile', 'update')->name('update');
            Route::put('password', 'changePassword')->name('changePassword');
        });

        Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('forgotPassword');
        Route::post('reset-password', [ForgotPasswordController::class, 'reset'])->name('resetPassword');
    });

Route::controller(AuthController::class)
    ->prefix('auth')
    ->name('auth.')
    ->group(function () {
        Route::post('login', 'login')->name('login');
        Route::middleware('auth:sanctum')->post('logout', 'logout')->name('logout');
    });

Route::controller(UserController::class)
    ->prefix('users')
    ->name('users.')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('{user}', 'show')->name('show');
        Route::put('{user}', 'update')->name('update');
        Route::delete('{user}', 'destroy')->name('destroy');
    });
