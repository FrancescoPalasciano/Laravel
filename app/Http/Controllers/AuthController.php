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

class AuthController extends Controller
{
    // Gestisce il login
    public function login(Request $request) {
        
        // 1. Validazione base
        $credentials = $request->validate([
            'email' => ['required', 'email', 'max:30'],
            'password' => ['required', 'max:20'],
        ], [
            'email.required' => "L'email è obbligatoria.",
            'email.email' => "Inserisci un indirizzo email valido.",
            'password.required' => "La password è obbligatoria.",
            'password.max' => "Errore, riprova.",
            'email.max' => "Errore, riprova.",
        ]);
        
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
        
        // 1. Validiamo i dati in arrivo
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'], 
            'password' => ['required', 'min:8', 'confirmed'], 
            'surname' => ['required', 'string', 'max:30'],
            'CF' => [
                'required', 
                'string', 
                'unique:users,CF', 
                'size:16',
                'regex:/^[A-Z]{6}[0-9LMNPQRSTUV]{2}[ABCDEHLMPRST]{1}[0-9LMNPQRSTUV]{2}[A-Z]{1}[0-9LMNPQRSTUV]{3}[A-Z]{1}$/i'
            ],
            'address' => ['nullable', 'string', 'max:100'],
            'phone' => [
                'nullable', 
                'string', 
                'unique:users,phone', 
                'min:9',   // Un numero di cellulare italiano ha minimo 9 cifre
                'max:13', 
                'regex:/^(\+39|0039)?\s?3\d{2}\s?\d{6,7}$/'
            ],
        ], [
            // Messaggi personalizzati per la Registrazione
            'name.required' => "Il nome è obbligatorio.",
            'name.string' => "Il nome deve essere un testo valido.",
            'name.max' => "Il nome non può superare 255 caratteri.",
            
            'email.required' => "L'email è obbligatoria.",
            'email.email' => "Il formato dell'email non è valido.",
            'email.unique' => "Questa email è già stata registrata.",
            
            'password.required' => "La password è obbligatoria.",
            'password.min' => "La password deve avere almeno :min caratteri.", 
            'password.confirmed' => "Le due password non coincidono.",

            'surname.required' => "Il cognome è obbligatorio.",
            'surname.string' => "Il cognome deve essere un testo valido.",
            'surname.max' => "Il cognome non può superare 30 caratteri.",

            'CF.required' => "Il codice fiscale è obbligatorio.",
            'CF.string' => "Il codice fiscale deve essere un testo valido.",
            'CF.unique' => "Questo codice fiscale è già stato registrato.",
            'CF.size' => "Il codice fiscale deve essere di esattamente 16 caratteri.",
            'CF.regex' => "Il codice fiscale inserito non è valido.",

            'address.string' => "L'indirizzo deve essere un testo valido.",
            'address.max' => "L'indirizzo non può superare 100 caratteri.",

            'phone.string' => "Il numero di telefono deve essere un testo valido.",
            'phone.unique' => "Questo numero di telefono è già stato registrato.",
            'phone.max' => "Il numero di telefono non può superare 13 caratteri.",
            'phone.min' => "Il numero di telefono deve avere almeno 9 caratteri.",
            'phone.regex' => "Il numero di telefono inserito non è valido.",
        ]);

        // 2. Creazione Utente nel Database

        $user = User::create([
            'name' => $validated['name'],
            'surname' => $validated['surname'],
            'CF' => $validated['CF'],
            'address' => $validated['address'] ?? null,
            'phone' => $validated['phone'] ?? null,
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

    function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        Log::info("Inizio updateUser per ID: " . $id, [
            'dati_attuali_db' => $user->toArray(),
            'dati_ricevuti_form' => $request->all()
        ]);


        // 1. Validiamo i dati in arrivo
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,'. $user->id], 
            'password' => ['nullable', 'min:8', 'confirmed'],
            'surname' => ['required', 'string', 'max:30'],
            'CF' => [
                'required', 
                'string', 
                'unique:users,CF,'. $user->id, 
                'size:16',
                'regex:/^[A-Z]{6}[0-9LMNPQRSTUV]{2}[ABCDEHLMPRST]{1}[0-9LMNPQRSTUV]{2}[A-Z]{1}[0-9LMNPQRSTUV]{3}[A-Z]{1}$/i'
            ],
            'address' => ['nullable', 'string', 'max:100'],
            'phone' => [
                'nullable', 
                'string', 
                'unique:users,phone,'. $user->id, 
                'min:9',   // Un numero di cellulare italiano ha minimo 9 cifre
                'max:13', 
                'regex:/^(\+39|0039)?\s?3\d{2}\s?\d{6,7}$/'
            ],
        ], [
            // Messaggi personalizzati per la Registrazione
            'name.required' => "Il nome è obbligatorio.",
            'name.string' => "Il nome deve essere un testo valido.",
            'name.max' => "Il nome non può superare 255 caratteri.",
            
            'email.required' => "L'email è obbligatoria.",
            'email.email' => "Il formato dell'email non è valido.",
            'email.unique' => "Questa email è già stata registrata.",
            
            'password.required' => "La password è obbligatoria.",
            'password.min' => "La password deve avere almeno :min caratteri.", 
            'password.confirmed' => "Le due password non coincidono.",

            'surname.required' => "Il cognome è obbligatorio.",
            'surname.string' => "Il cognome deve essere un testo valido.",
            'surname.max' => "Il cognome non può superare 30 caratteri.",

            'CF.required' => "Il codice fiscale è obbligatorio.",
            'CF.string' => "Il codice fiscale deve essere un testo valido.",
            'CF.unique' => "Questo codice fiscale è già stato registrato.",
            'CF.size' => "Il codice fiscale deve essere di esattamente 16 caratteri.",
            'CF.regex' => "Il codice fiscale inserito non è valido.",

            'address.string' => "L'indirizzo deve essere un testo valido.",
            'address.max' => "L'indirizzo non può superare 100 caratteri.",

            'phone.string' => "Il numero di telefono deve essere un testo valido.",
            'phone.unique' => "Questo numero di telefono è già stato registrato.",
            'phone.max' => "Il numero di telefono non può superare 13 caratteri.",
            'phone.min' => "Il numero di telefono deve avere almeno 9 caratteri.",
            'phone.regex' => "Il numero di telefono inserito non è valido.",
        ]);
            

        // 2. Aggiornamento utente nel database
        $data = [
        'name' => $validated['name'],
        'surname' => $validated['surname'],
        'email' => $validated['email'],
        'CF' => $validated['CF'],
        'address' => $validated['address'],
        'phone' => $validated['phone'],
        ];

        if ($request->filled('password')) {
        $data['password'] = Hash::make($validated['password']);
        }

        try {
        $success = $user->update($data);

        if ($success) {
            Log::info("Update riuscito per utente " . $id);
            session()->forget('user_to_edit');
            return redirect()->route('utenti')->with('status', 'Utente aggiornato con successo.');
        }

        return back()->withInput()->with('error', 'Errore durante l\'update.');

        } catch (\Exception $e) {
            Log::error("Eccezione Database: " . $e->getMessage());
            return back()->withInput()->with('error', 'Errore tecnico: ' . $e->getMessage());
        }
    }


    // cancello l'utente
    function deleteUser(Request $request) {

        $authuser = Auth::user();
        if ($authuser->id == $request->user_id) {
            return redirect()->route('utenti')->with('error', 'Non puoi eliminare il tuo stesso account.');
        }

        $user = User::findOrFail($request->user_id); // Prendi l'id dal request body
        $user->delete();

        return redirect()->route('utenti')->with('status', 'Utente eliminato con successo.');
    }
}