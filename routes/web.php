<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UtentiController;
use Illuminate\Http\Request;
use App\Models\User;

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

Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard')->middleware('auth');

// prendo i dati degli utenti e li passo a utenti.blade

Route::get('/utenti', [UtentiController::class, 'utenti'])->name('utenti')->middleware('auth');

// Post
Route::post('/login', [AuthController::class, 'login']);

Route::post('/registration', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/password-request', [AuthController::class, 'sendResetLink'])->name('sendResetLink');

Route::post('/reset-password', [AuthController::class, 'updatePassword'])->name('password.update');

// post delete user
Route::post('/delete-user', [AuthController::class, 'deleteUser'])->name('delete-user')->middleware('auth');


// edit user
Route::post('/modifica', function(Request $request) {
    // Salviamo l'ID nella sessione dell'utente
    session(['user' => $request->user_id]);
    
    // Reindirizziamo alla rotta che mostra la pagina di modifica
    return redirect()->route('modifica utente');
})->name('modifica')->middleware('auth')->middleware('web');

// 2. Mostra la pagina recuperando l'ID dalla sessione
Route::get('/modifica-utente', function() {
    $id = session('user');

    if (!$id) {
        return redirect('/utenti')->with('error', 'Nessun utente selezionato');
    }

    $user = User::findOrFail($id);
    return view('auth.pages.edit-user', compact('user'));
})->name('modifica utente')->middleware('auth')->middleware('web');

Route::put('/update-user/{id}', [AuthController::class, 'updateUser'])->name('update-user')->middleware('auth');