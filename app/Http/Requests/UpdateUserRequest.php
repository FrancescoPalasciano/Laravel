<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Ricorda di impostarlo a true
        return true;
    }

    public function rules(): array
    {
        // Recuperiamo l'ID dell'utente dalla rotta. 
        // Se la tua rotta è /users/{user}, $this->route('user') prenderà l'ID o il modello.
        $userId = $this->route('user')->id? $this->route('user')->id : null;

        return [
            // Unique ma ignora l'utente corrente
            'name'     => ['required', 'string', 'max:30'],
            'surname'  => ['required', 'string', 'max:30'],
            'address'  => ['nullable', 'string', 'max:100'],
            'email'    => ['required', 'email', Rule::unique('users')->ignore($userId),], 
            'CF'       => [
                'required', 
                'string', 
                'size:16',
                Rule::unique('users')->ignore($userId),
                'regex:/^[A-Za-z]{6}[0-9]{2}[A-Za-z]{1}[0-9]{2}[A-Za-z]{1}[0-9]{3}[A-Za-z]{1}$/'
                ], 
            'phone'    => [
                'nullable', 
                'string', 
                'min:9',
                'max:15', 
                Rule::unique('users')->ignore($userId),
                'regex:/^\\+?[1-9][0-9]{7,14}$/'
            ],
            'password' => ['nullable', 'min:8', 'confirmed'], 
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => "Il nome è obbligatorio.",
            'name.string'        => "Il nome deve essere un testo valido.",
            'name.max'           => "Il nome non può superare 30 caratteri.",
            'surname.required'   => "Il cognome è obbligatorio.",
            'surname.string'     => "Il cognome deve essere un testo valido.",
            'surname.max'        => "Il cognome non può superare 30 caratteri.",
            'address.string'     => "L'indirizzo deve essere un testo valido.",
            'address.max'        => "L'indirizzo non può superare 100 caratteri.",
            'email.required'     => "L'email è obbligatoria.",
            'email.email'        => "Il formato dell'email non è valido.",
            'password.min'       => "La password deve avere almeno :min caratteri.", 
            'password.confirmed' => "Le due password non coincidono.",
            'CF.required'        => "Il codice fiscale è obbligatorio.",
            'CF.size'            => "Il codice fiscale deve essere di esattamente 16 caratteri.",
            'CF.regex'           => "Il formato del codice fiscale non è corretto.",
            'phone.regex'        => "Il numero di telefono inserito non è valido.",
            'phone.min'          => "Il numero di telefono deve avere almeno :min caratteri.",
            'phone.max'          => "Il numero di telefono non può superare :max caratteri.",
            'phone.unique'       => "Questo numero di telefono è già stato registrato.",
            'email.unique'       => "Questa email è già stata registrata.",
            'CF.unique'          => "Questo codice fiscale è già stato registrato.",
            'password.min'       => "La nuova password deve avere almeno :min caratteri.", 
            'password.confirmed' => "Le password non coincidono.",
        ];
    }
}