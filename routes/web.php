<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;

/**
 * Ruta para la página de inicio de sesión.
 * Aplica el middleware 'guest' para redirigir a los usuarios autenticados.
 */
Route::get('/', function () {
    return view('auth.login');
})->middleware('guest');

/**
 * Grupo de rutas que requieren autenticación y verificación de dos factores.
 */
Route::middleware(['auth', '2fa.verified'])->group(function () {
    Route::view('/home', 'home')->name('home');
});

/**
 * Grupo de rutas para usuarios invitados (no autenticados).
 */
Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.login')->name('login');
    Route::view('/register', 'auth.register')->name('register');
    Route::view('/authentication', 'auth.authentication')->name('authentication');
});

/**
 * Rutas para acciones de autenticación.
 */
Route::post('/register', [AuthController::class, 'register'])->name('register.action');
Route::post('/login', [AuthController::class, 'login'])->name('login.action');
Route::post('/verify', [AuthController::class, 'verifyTwoFactor'])->name('verify.action');

/**
 * Ruta para cerrar sesión.
 * Cierra la sesión del usuario y redirige a la página de inicio de sesión.
 */
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

/**
 * Ruta para verificar el correo electrónico del usuario.
 */
Route::get('/verify-email/{user}', [AuthController::class, 'verifyEmail'])->name('verify.email');