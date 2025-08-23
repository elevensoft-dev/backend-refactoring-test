<?php

use App\Http\Controllers\AuthController;
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

Route::prefix('auth')->controller(AuthController::class)->group(function (){
    Route::post('login', 'login');
    Route::middleware('auth:api')->group(function () {
        Route::post('logout', 'logout');
        Route::get('me', 'me');
    });
});

Route::prefix('users')->controller(UserController::class)->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::post('/list', 'paginateUsers');
        Route::get('/{id}', 'getUserById');
        Route::post('/create', 'createUser');
    });
});
