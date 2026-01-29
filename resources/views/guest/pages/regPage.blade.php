@extends('guest.master-login')

@section('title', 'Registration Page')

@section('content-guest')
    <div class="flex items-center justify-center min-h-50vh bg-gray-100">

        <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-sm border border-gray-100">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-blue-600">Benvenuto</h1>
                <p class="text-gray-500 text-sm mt-2">Registra il tuo account</p>
            </div>

            <form action="/registration" method="POST" class="space-y-6">
            @csrf 

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nome e Cognome</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    placeholder="Il tuo nome"
                    maxlength="50"
                    class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white transition duration-200"
                    required
                >
            </div>

            <div>
                <label for="surname" class="block text-sm font-medium text-gray-700 mb-2">Cognome</label>
                <input 
                    type="text" 
                    id="surname" 
                    name="surname" 
                    placeholder="Il tuo Cognome" 
                    maxlength="30"
                    class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white transition duration-200"
                    required
                >
            </div>

            <div>
                <label for="CF" class="block text-sm font-medium text-gray-700 mb-2">Codice Fiscale</label>
                <input 
                    type="text" 
                    id="CF" 
                    name="CF" 
                    placeholder="Il tuo Codice Fiscale" 
                    maxlength="16"
                    class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white transition duration-200"
                    required
                >
            </div>

            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Indirizzo</label>
                <input 
                    type="text" 
                    id="address" 
                    name="address" 
                    placeholder="La tua via e città" 
                    maxlength="100"
                    class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white transition duration-200"
                >
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Numero di Telefono</label>
                <input 
                    type="text" 
                    id="phone" 
                    name="phone" 
                    placeholder="Il tuo numero di telefono" 
                    maxlength="15"
                    class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white transition duration-200"
                >
            </div>
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="nome@esempio.com" 
                    maxlength="40"
                    class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white transition duration-200"
                    required
                >
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    maxlength="20" 
                    placeholder="••••••••" 
                    class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white transition duration-200"
                    required
                >
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Conferma Password</label>
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    maxlength="20" 
                    placeholder="••••••••" 
                    class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white transition duration-200"
                    required
                >
            </div>

            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4 text-sm border border-red-200">
                    {{ $errors->first() }}
                </div>
            @endif

            <button 
                type="submit" 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-0.5"
            >
                Registrati
            </button>

            </form>
            
            <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                <p class="text-sm text-gray-600">
                    Hai già un account?
                    <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:text-blue-800 hover:underline ml-1">
                        Accedi
                    </a>
                </p>
            </div>

        </div>
    </div>
@endsection