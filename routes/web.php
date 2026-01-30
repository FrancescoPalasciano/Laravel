<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UtentiController;

Route::get('/', function () {
    return view('guest.pages.home');
})->name('home');

Route::get('/login', function () {
    return view('guest.pages.loginPage');
})->name('login');

Route::get('/register', function () {
    return view('guest.pages.regPage');
})->name('registrazione');

Route::get('/password-request', function () {
    return view('guest.pages.password-request');
})->name('password-request');

Route::get('/reset-password/{token}', function (string $token) {
    return view('guest.pages.reset-password', [
        'token' => $token, 
        'email' => request('email') // L'email arriva nell'URL come parametro
    ]);
})->name('password.reset');

// Post
Route::post('/login', [AuthController::class, 'login']);

Route::post('/registration', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/password-request', [UtentiController::class, 'sendResetLink'])->name('sendResetLink');

Route::post('/reset-password', [UtentiController::class, 'updatePassword'])->name('password.update');

Route::group(['middleware' => 'auth'], function () {
    // prendo i dati degli utenti e li passo a utenti.blade.php
    Route::get('/utenti', [UtentiController::class, 'utenti'])->name('utenti');

    Route::post('/delete-user', [UtentiController::class, 'deleteUser'])->name('Elimina');

    Route::put('/update-user/{user}', [UtentiController::class, 'updateUser'])->name('Aggiorna');
    //modifica in get
    Route::get('/visualizza/{id}', [UtentiController::class, 'visualizza'])->name('Modifica');

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
});

