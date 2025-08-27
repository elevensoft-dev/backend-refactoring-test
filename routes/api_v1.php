<?php

use App\Http\Controllers\User\V1\CreateNewUserController;
use App\Http\Controllers\User\V1\DeleteUserController;
use App\Http\Controllers\User\V1\GetAllUsersController;
use App\Http\Controllers\User\V1\GetAllUsersPaginatedController;
use App\Http\Controllers\User\V1\GetUserByIdController;
use App\Http\Controllers\User\V1\UpdateUserController;
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
    Route::get('/all', GetAllUsersController::class)
        ->name('users.all');
    Route::get('/all/paginate', GetAllUsersPaginatedController::class)
        ->name('users.all.paginate');
    Route::post('/new', CreateNewUserController::class)
        ->name('user.store');
    Route::get('/by-id/{id}', GetUserByIdController::class)
        ->name('user.get-by-id');
    Route::patch('/update/{id}', UpdateUserController::class)
        ->name('user.update');
    Route::delete('/remove/{id}', DeleteUserController::class)
        ->name('user.delete');
});
