<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determina se l'utente è autorizzato a fare questa richiesta.
     */
    public function authorize(): bool
    {
        // IMPORTANTE: Impostato a true per permettere la richiesta
        return true; 
    }

    /**
     * Regole di validazione.
     */
    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:30'],
            'email'    => ['required', 'email', 'unique:users,email'], 
            'password' => ['required', 'min:8', 'confirmed'], 
            'surname'  => ['required', 'string', 'max:30'],
            'CF'       => [
                'required', 
                'string', 
                'unique:users,CF', 
                'size:16',
                'regex:/^[A-Z]{6}[0-9LMNPQRSTUV]{2}[ABCDEHLMPRST]{1}[0-9LMNPQRSTUV]{2}[A-Z]{1}[0-9LMNPQRSTUV]{3}[A-Z]{1}$/i'
            ],
            'address'  => ['nullable', 'string', 'max:100'],
            'phone'    => [
                'nullable', 
                'string', 
                'unique:users,phone', 
                'min:9',
                'max:13', 
                'regex:/^\+?[1-9][0-9]{7,14}$/'
            ],
        ];
    } 

    /**
     * Messaggi personalizzati.
     */
    public function messages(): array
    {
        return [
            'name.required'     => "Il nome è obbligatorio.",
            'name.string'       => "Il nome deve essere un testo valido.",
            'name.max'          => "Il nome non può superare 30 caratteri.",
            
            'email.required'    => "L'email è obbligatoria.",
            'email.email'       => "Il formato dell'email non è valido.",
            'email.unique'      => "Questa email è già stata registrata.",
            
            'password.required' => "La password è obbligatoria.",
            'password.min'      => "La password deve avere almeno :min caratteri.", 
            'password.confirmed'=> "Le due password non coincidono.",

            'surname.required'  => "Il cognome è obbligatorio.",
            'surname.max'       => "Il cognome non può superare 30 caratteri.",

            'CF.required'       => "Il codice fiscale è obbligatorio.",
            'CF.unique'         => "Questo codice fiscale è già stato registrato.",
            'CF.size'           => "Il codice fiscale deve essere di esattamente 16 caratteri.",
            'CF.regex'          => "Il codice fiscale inserito non è valido.",

            'phone.unique'      => "Questo numero di telefono è già stato registrato.",
            'phone.regex'       => "Il numero di telefono inserito non è valido.",
        ];
    }
}