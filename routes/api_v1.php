<?php

use App\Http\Controllers\Delete\User\V1\DeleteUserController;
use App\Http\Controllers\Get\User\V1\AllUsersController;
use App\Http\Controllers\Get\User\V1\UserByIdController;
use App\Http\Controllers\Post\User\V1\CreateNewUserController;
use App\Http\Controllers\PutPatch\User\V1\UpdateUserController;
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

Route::group(['prefix'=> 'users'], function (): void {
    Route::get('/all', AllUsersController::class)
        ->name('users.all');
    Route::post('/new', CreateNewUserController::class)
        ->name('user.store');
    Route::get('/by-id/{id}', UserByIdController::class)
        ->name('user.get-by-id');
    Route::patch('/update/{id}', UpdateUserController::class)
        ->name('user.update');
    Route::delete('/remove/{id}', DeleteUserController::class)
        ->name('user.delete');
});
