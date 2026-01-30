
@extends('auth.master-auth')

@section('title', 'Utenti')

@section('content-auth')
    @props(['user' => $user ?? null])

<div class="flex flex-1 flex-col">
    <div class="@container/main flex flex-1 flex-col gap-2">
        <div class="flex flex-col gap-4 py-4 md:gap-6 md:py-6">   
            <h1 class="text-3xl font-bold px-4 lg:px-6">Utenti / Visualizza</h1>
                <div class="px-4 lg:px-6"> 
                    <form action="{{ route('Aggiorna', $user) }}" method="POST" class="space-y-6">
                        @csrf 
                        @method('PUT') 
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nome</label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    value="{{ old('name', $user->name) }}"
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
                                    value="{{ old('surname', $user->surname) }}"
                                    placeholder="Il tuo Cognome" 
                                    maxlength="30"
                                    class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white transition duration-200"
                                    required
                                >
                            </div>
                        </div>

                        <div>
                            <label for="CF" class="block text-sm font-medium text-gray-700 mb-2">Codice Fiscale</label>
                            <input 
                                type="text" 
                                id="CF" 
                                name="CF" 
                                value="{{ old('CF', $user->CF) }}"
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
                                value="{{ old('address', $user->address) }}"
                                placeholder="La tua via e città" 
                                maxlength="100"
                                class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white transition duration-200"
                            >
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Numero di Telefono</label>
                                <input 
                                    type="text" 
                                    id="phone" 
                                    name="phone" 
                                    value="{{ old('phone', $user->phone) }}"
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
                                    value="{{ old('email', $user->email) }}"
                                    placeholder="nome@esempio.com" 
                                    maxlength="40"
                                    class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white transition duration-200"
                                    required
                                >
                            </div>
                        </div>

                        <hr class="border-gray-200 my-6">
                        <p class="text-s text-gray-500 mb-4 italic">Lascia la password vuota se non desideri cambiarla.</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Nuova Password</label>
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    maxlength="20" 
                                    placeholder="••••••••" 
                                    class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white transition duration-200"
                                >
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Conferma Nuova Password</label>
                                <input 
                                    type="password" 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    maxlength="20" 
                                    placeholder="••••••••" 
                                    class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white transition duration-200"
                                >
                            </div>
                        </div>

                        @if ($errors->any())
                            <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4 text-sm border border-red-200">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <div class="flex items-center gap-4 pt-4">
                            <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                                Salva Modifiche
                            </button>
                            <a href="/utenti" class="px-6 py-3 bg-white text-gray-700 font-semibold rounded-lg border border-gray-300 hover:bg-gray-50 transition duration-200 text-center">
                                Indietro
                            </a>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</div>

    
@endsection