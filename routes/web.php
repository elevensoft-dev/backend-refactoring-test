<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
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

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/user', function () {
    return view('userPage');
})->name('user.page');

Route::get('/users', function () {
    return view('users');
})->name('users.index');

Route::post('/users', [UserController::class, 'store'])->name('users.store');

Route::get('/createUser', function () {
    return view('createUser');
})->name('users.create');

Route::post('/render-users', function (\Illuminate\Http\Request $request) {
    $users = $request->input('users', []);
    return view('components.usersList', compact('users'))->render();
})->name('users.render');




