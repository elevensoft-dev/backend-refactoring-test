<?php

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
Route::apiResource('users', UserController::class)->names([
    'index' => 'api.users.index',
    'store' => 'api.users.store',
    'show' => 'api.users.show',
    'update' => 'api.users.update',
    'destroy' => 'api.users.destroy',
]);
