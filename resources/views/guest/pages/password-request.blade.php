@extends('guest.master-login')

@section('title', 'Recupero Password')

@section('content-guest')
    <div class="flex items-center justify-center min-h-[60vh] bg-gray-100">

        <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-sm border border-gray-100">
            
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-blue-600">Password Dimenticata?</h1>
                <p class="text-gray-500 text-sm mt-2">Nessun problema. Inserisci la tua email e ti invieremo un link per resettarla.</p>
            </div>

            @if (session('status'))
                <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-6 text-sm border border-green-200 text-center">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('password-request') }}" method="POST" class="space-y-6">
            @csrf 
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    placeholder="nome@esempio.com" 
                    class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white transition duration-200"
                    required
                    autofocus
                >
            </div>

            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4 text-sm border border-red-200">
                    {{-- first() vuoto prende il primo errore di QUALSIASI campo --}}
                    {{ $errors->first() }}
                </div>
            @endif

            <button 
                type="submit" 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-0.5"
            >
                Invia Link di Reset
            </button>

            </form>
            
            <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                <a href="/login" class="text-sm font-bold text-gray-600 hover:text-gray-800 transition-colors flex items-center justify-center gap-2">
                    <span>←</span> Torna al Login
                </a>
            </div>

        </div>
    </div>
@endsection