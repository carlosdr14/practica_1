<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth', '2fa.verified'])->group(function () {
    Route::view('/home', 'home')->name('home');
});

Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.login')->name('login');
    Route::view('/register', 'auth.register')->name('register');
    Route::view('/authentication', 'auth.authentication')->name('authentication');
});


//actions
Route::post('/register', [AuthController::class, 'register'])->name('register.action');
Route::post('/login', [AuthController::class, 'login'])->name('login.action');
Route::post('/verify', [AuthController::class, 'verifyTwoFactor'])->name('verify.action');
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');
Route::get('/verify-email/{user}', [AuthController::class, 'verifyEmail'])->name('verify.email');
