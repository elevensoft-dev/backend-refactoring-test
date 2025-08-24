<?php

use App\Http\Controllers\Delete\User\DeleteUserController;
use App\Http\Controllers\Get\User\AllUsersController;
use App\Http\Controllers\Get\User\UserByIdController;
use App\Http\Controllers\Post\User\CreateNewUserController;
use App\Http\Controllers\PutPatch\User\UpdateUserController;
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
//Route::apiResource('users', UserController::class);

Route::group(['middleware' => '', 'prefix'=> ''], function (): void {
    Route::group(['prefix'=> 'users'], function (): void {
        Route::get('/', AllUsersController::class);
        Route::post('/', CreateNewUserController::class);
        Route::get('/{id}', UserByIdController::class);
        Route::patch('/{id}', UpdateUserController::class);
        Route::delete('/{id}', DeleteUserController::class);
    });
});
