<?php

namespace App\Http\Controllers;

use App\Models\User;

class UtentiController extends Controller
{
    public function utenti()
    {
        $data = User::all()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at ? $user->email_verified_at->format('d/m/Y H:i:s') : 'Non verificata',
                'created_at' => $user->created_at->format('d/m/Y'),
            ];
        })->toArray();

        return view('auth.pages.utenti', ['data' => $data]);
    }
}
