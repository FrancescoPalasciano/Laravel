<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\UpdateUserRequest;

class AuthController extends Controller
{

    // Gestisce il login
    public function login(Request $request) {

        
        // 1. Validazione base
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $request->input('email'))->first();
        // Controlliamo se l'utente esiste e se è disabilitato
        if ($user && $user->status === 'Inactive') {
            return back()->withErrors([
                'email' => "Il tuo account è disabilitato.",
            ])->onlyInput('email');
        }
        
        // Creiamo una chiave unica per questo utente (basata su email e indirizzo IP)
        $throttleKey = 'login:' . Str::lower($request->input('email')) . '|' . $request->ip();

        // Controlliamo se ha fatto troppi tentativi (es. max 5 tentativi)
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            // Calcoliamo quanti secondi mancano allo sblocco
            $seconds = RateLimiter::availableIn($throttleKey);

            // Blocchiamo l'esecuzione lanciando un errore di validazione
            throw ValidationException::withMessages([
                'email' => "Troppi tentativi di accesso. Riprova tra $seconds secondi.",
            ]);
        }

        // --- LOGIN ---

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            
            // Login riuscito: cancello i tentativi falliti registrati
            RateLimiter::clear($throttleKey);

            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        // --- LOGIN FALLITO ---

        // Aumentiamo il contatore dei tentativi falliti (blocca per 60 secondi dopo i 5 errori)
        RateLimiter::hit($throttleKey, 60);

        return back()->withErrors([
            'email' => "Credenziali errate.",
        ])->onlyInput('email');
    }


    // Funzione che gestisce la REGISTRAZIONE
    public function register(UpdateUserRequest $request) {
        
        // 1. Validiamo i dati in arrivo
        $validated = $request->validated();

        // 2. Creazione Utente nel Database

        $user = User::save([
            'name' => $validated['name'],
            'surname' => $validated['surname'],
            'CF' => $validated['CF'],
            'address' => $validated['address'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']), 
        ]);

        // 4. Reindirizza alla login con un messaggio di successo (se vuoi)
        return back()->with('status', 'Registrazione utente completata!');
    }

    public function logout(UpdateUserRequest $request) {
        // 1. Esegue il logout
        Auth::logout();

        // 2. Invalida la sessione (per sicurezza)
        $request->session()->invalidate();

        // 3. Rigenera il token CSRF (per sicurezza)
        $request->session()->regenerateToken();

        // 4. Reindirizza alla HOME
        return redirect('/')->with('status', 'Logout completato!'); 
    }

    
}