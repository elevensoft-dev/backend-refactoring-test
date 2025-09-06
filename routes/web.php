<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('email/verify/{id}/{hash}', function () {
    return response('Email verification placeholder.', 200);
})->name('verification.verify');

Route::get('password/reset/{token}', function () {
    return response('Password reset placeholder.', 200);
})->name('password.reset');

Route::get('/', function () {
    return view('welcome');
});
