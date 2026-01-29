<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
}
