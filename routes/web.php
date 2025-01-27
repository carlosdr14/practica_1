<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::get('/', function () {
    return view('auth.login');
});

//views
Route::view('/login', 'auth.login')->name('login'); // view
Route::view('/register', 'auth.register')->name('register'); // view
Route::view('/authentication', 'auth.authentication')->name('authentication'); // view
Route::view('/home', 'home')->name('home')->middleware(['auth', '2fa.verified']); // view


//actions
Route::post('/register', [AuthController::class, 'register'])->name('register.action');
Route::post('/login', [AuthController::class, 'login'])->name('login.action');
Route::post('/verify', [AuthController::class, 'verifyTwoFactor'])->name('verify.action');

Route::get('/verify-email/{user}', [AuthController::class, 'verifyEmail'])->name('verify.email');
