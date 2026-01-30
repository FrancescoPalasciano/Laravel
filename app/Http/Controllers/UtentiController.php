<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Log;

class UtentiController extends Controller
{
    public function utenti()
    {
        $data = User::all()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'surname' => $user->surname,
                'phone' => $user->phone,
                'CF' => $user->CF,
                'address' => $user->address,
                'email' => $user->email,
                'created_at' => $user->created_at->format('d/m/Y'),
            ];
        })->toArray();

        return view('auth.pages.utenti', ['data' => $data]);
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

    function updateUser(User $user,Request $request)
    {

        // dd($Olduser);
        
        Log::info("Inizio updateUser per ID: " . $user->id, [
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
                'regex:/^[A-Za-z]{6}[0-9]{2}[A-Za-z]{1}[0-9]{2}[A-Za-z]{1}[0-9]{3}[A-Za-z]{1}$/'
            ],
            'address' => ['nullable', 'string', 'max:100'],
            'phone' => [
                'nullable', 
                'string', 
                'unique:users,phone,'. $user->id, 
                'min:9',   // Un numero di cellulare italiano ha minimo 9 cifre
                'max:13', 
                'regex:/^\\+?[1-9][0-9]{7,14}$/'
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
        $user['name'] = $validated['name'];
        $user['surname'] = $validated['surname'];
        $user['CF'] = $validated['CF'];
        $user['address'] = $validated['address'] ?? null;
        $user['phone'] = $validated['phone'] ?? null;
        $user['email'] = $validated['email'];

        if ($request->filled('password')) {
            $user['password'] = Hash::make($validated['password']);
        }

        try {
            $success = $user->save();

            if ($success) {
                Log::info("Update riuscito per utente " . $user->id);
                return redirect()->route('utenti')->with('status', 'Utente aggiornato con successo.');
            }

            return back()->withInput()->with('error', 'Errore durante l\'update.');

        } catch (\Exception $e) {
            Log::error("Eccezione Database: " . $e->getMessage());
            return back()->withInput()->with('error', 'Errore tecnico: ' . $e->getMessage());
        }
    }

    public function visualizza($id) {
        $user = User::findOrFail($id);
        return view('auth.pages.edit-user', compact('user'));
    }
}
