<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\CpfLoginController;
use Illuminate\Http\Request;


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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/login', [CpfLoginController::class, 'login']);
Route::post('/logout', [CpfLoginController::class, 'logout'])->name('logout');
Route::put('login/{id}/password', [CpfLoginController::class, 'updatePassword']);

Route::apiResource('users', UserController::class);

// Rotas personalizadas para CPF
Route::get('users/cpf/{cpf}', [UserController::class, 'showByCpf']);
Route::put('users/cpf/{cpf}', [UserController::class, 'updateByCpf']);
Route::delete('users/cpf/{cpf}', [UserController::class, 'destroyByCpf']);

