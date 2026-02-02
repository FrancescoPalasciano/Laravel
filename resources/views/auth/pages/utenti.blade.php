@extends('auth.master-auth')

@section('title', 'Utenti')

@section('content-auth')
    @props(['data' => $data ?? []])

<div id="utenti-container" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/20 backdrop-blur-sm p-4 pointer-events-none [&:not(.hidden)]:pointer-events-auto">
    
    <div class="bg-white/90 p-8 rounded-2xl shadow-xl w-full max-w-2xl border border-gray-100 my-auto">
        
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-blue-600">Registrazione Utente</h1>
            <!-- <p class="text-gray-500 text-sm mt-2">Registra il tuo account</p> -->
        </div>

        <form action="/registration" method="POST">
            @csrf 

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nome</label>
                    <input type="text" id="name" name="name" maxlength="50" class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-200" required>
                </div>

                <div>
                    <label for="surname" class="block text-sm font-medium text-gray-700 mb-2">Cognome</label>
                    <input type="text" id="surname" name="surname" maxlength="30" class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-200" required>
                </div>

                <div>
                    <label for="CF" class="block text-sm font-medium text-gray-700 mb-2">Codice Fiscale</label>
                    <input type="text" id="CF" name="CF" maxlength="16" class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-200" required>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Numero di Telefono</label>
                    <input type="text" id="phone" name="phone" maxlength="15" class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-200">
                </div>

                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Indirizzo</label>
                    <input type="text" id="address" name="address" maxlength="100" class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-200">
                </div>

                <div class="md:col-span-2">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" id="email" name="email" maxlength="40" class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-200" required>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-200" required>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Conferma Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-200" required>
                </div>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-3 rounded-lg mt-6 text-sm border border-red-200">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="flex gap-4 mt-8">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg transition duration-300 transform hover:-translate-y-0.5">
                    Aggiungi Utente
                </button>
                <button type="button" onclick="document.getElementById('utenti-container').classList.add('hidden')" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-xl transition duration-300">
                    Chiudi
                </button>
            </div>
        </form>
    </div>
</div>

<div id="alert" class="hidden fixed inset-0 z-[10000] flex items-center justify-center bg-black/30 backdrop-blur-sm p-4 pointer-events-none [&:not(.hidden)]:pointer-events-auto">
    <form id="delete-form" action="/delete-user" method="POST" class="bg-white/95 p-8 rounded-3xl shadow-2xl w-full max-w-md border border-gray-100 my-auto text-center transform transition-all">
        @csrf
        <input type="hidden" name="user_id" id="delete-user-id" value="">

        <p class="text-2xl font-extrabold text-gray-900">Sei sicuro?</p>
        <p class="text-gray-500 mt-2 text-sm leading-relaxed">
            Stai per eliminare definitivamente questo utente. <br>Questa operazione non può essere annullata.
        </p>
        
        <div class="grid grid-cols-2 gap-3 mt-8">
            <button type="button" 
                id="annulla"
                onclick="document.getElementById('alert').classList.add('hidden')" 
                class="px-4 py-3 bg-gray-100 text-gray-700 font-bold rounded-2xl transition-all duration-200 hover:bg-gray-200 hover:scale-[1.02] active:scale-95">
                Annulla
            </button>

            <button type="submit" 
                id="elimina"
                class="px-4 py-3 bg-red-600 text-white font-bold rounded-2xl shadow-md transition-all duration-200 hover:bg-red-700 hover:shadow-red-500/30 hover:scale-[1.02] active:scale-95 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                Elimina
            </button>
        </div>
    </form>
</div>

<div class="flex flex-1 flex-col">
    <div class="px-4 lg:px-6 py-4 md:py-6">
        <div class="fixed top-5 left-1/2 -translate-x-1/4 z-[1000] w-full max-w-md px-4 pointer-events-none">
    
            @if (session('error'))
                <div class="pointer-events-auto bg-red-100 text-red-700 p-4 rounded-xl mb-4 text-sm border border-red-200 shadow-xl text-center font-medium">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('status'))
                <div class="pointer-events-auto bg-green-100 text-green-700 p-4 rounded-xl mb-4 text-sm border border-green-200 shadow-xl text-center font-medium">
                    {{ session('status') }}
                </div>
            @endif
            
        </div>
        <div class="w-full"> 
            <data-table :data='@json($data)'></data-table> 
        </div>
    </div>
</div>
@endsection