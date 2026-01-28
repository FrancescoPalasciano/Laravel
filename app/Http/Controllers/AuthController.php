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

class AuthController extends Controller
{
    // Gestisce il login
    public function login(Request $request) {
        
        // 1. Validazione base
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => "L'email è obbligatoria.",
            'email.email' => "Inserisci un indirizzo email valido.",
            'password.required' => "La password è obbligatoria.",
        ]);

        // --- GESTIONE RATE LIMITER (NUOVO) ---
        
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
            
            // Login riuscito: CANCELLIAMO i tentativi falliti registrati
            RateLimiter::clear($throttleKey);

            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        // --- LOGIN FALLITO ---

        // Aumentiamo il contatore dei tentativi falliti (blocca per 60 secondi dopo i 5 errori)
        RateLimiter::hit($throttleKey, 60);

        // Calcoliamo quanti tentativi rimangono per mostrarlo all'utente (opzionale)
        $tentativiRimasti = RateLimiter::retriesLeft($throttleKey, 5);

        return back()->withErrors([
            'email' => "Credenziali errate.",
        ])->onlyInput('email');
    }


    // Funzione che gestisce la REGISTRAZIONE
    public function register(Request $request) {
        
        // 1. Validiamo i dati in arrivo con messaggi in ITALIANO
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'], 
            'password' => ['required', 'min:6', 'confirmed'], 
        ], [
            // Messaggi personalizzati per la Registrazione
            'name.required' => "Il nome è obbligatorio.",
            'name.string' => "Il nome deve essere un testo valido.",
            'name.max' => "Il nome non può superare 255 caratteri.",
            
            'email.required' => "L'email è obbligatoria.",
            'email.email' => "Il formato dell'email non è valido.",
            'email.unique' => "Questa email è già stata registrata.",
            
            'password.required' => "La password è obbligatoria.",
            'password.min' => "La password deve avere almeno :min caratteri.", // :min viene sostituito automaticamente con 6
            'password.confirmed' => "Le due password non coincidono.", // Questo risolve il tuo errore precedente
        ]);

        // 2. Creazione Utente nel Database
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']), 
        ]);

        // 4. Reindirizza alla login con un messaggio di successo (se vuoi)
        return redirect('/login')->with('status', 'Registrazione completata! Ora puoi accedere.');
    }

    public function logout(Request $request) {
        // 1. Esegue il logout
        Auth::logout();

        // 2. Invalida la sessione (per sicurezza)
        $request->session()->invalidate();

        // 3. Rigenera il token CSRF (per sicurezza)
        $request->session()->regenerateToken();

        // 4. Reindirizza alla HOME
        return redirect('/')->with('status', 'Logout completato!'); 
    }

    public function sendResetLink(Request $request)
    {
        // 1. Validazione base
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => "L'email è obbligatoria.",
            'email.email' => "Inserisci un indirizzo email valido.",
        ]);

        // Laravel controlla se l'email esiste e crea un token
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // 3. Controllo del risultato
        if ($status === Password::RESET_LINK_SENT) {
            // SUCCESSO: Torna indietro con messaggio verde
            return back()->with('status', 'Link di reset inviato! Controlla la tua casella di posta (anche nello spam).');
        }

        // ERRORE: Torna indietro con errore sul campo email
        return back()->withErrors([
            'email' => 'Non riusciamo a trovare un utente con questo indirizzo email.',
        ]);
    }

    public function updatePassword(Request $request)
{
    // 1. Validazione
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:6|confirmed',
    ]);

    // 2. Aggiornamento Password (tutto automatico grazie a Laravel)
    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        }
    );

    // 3. Risultato
    if ($status === Password::PASSWORD_RESET) {
        return redirect('/login')->with('status', 'La tua password è stata resettata!');
    }

    return back()->withErrors(['email' => [__($status)]]);
}
}