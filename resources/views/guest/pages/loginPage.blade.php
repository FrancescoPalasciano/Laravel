@extends('guest.master-login')

@section('title', 'loginPage')

@section('content-guest')
    <div class="flex items-center justify-center min-h-[80vh] bg-gray-100">

        <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-sm border border-gray-100">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-blue-600">Benvenuto</h1>
                <p class="text-gray-500 text-sm mt-2">Accedi al tuo account</p>
            </div>

            <form action="/login" method="POST" class="space-y-6">
            @csrf 
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    placeholder="nome@esempio.com" 
                    maxlength="40"
                    class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent focus:bg-white transition duration-200"
                    required
                >
            </div>

            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    
                    <a href="{{ route('password-request') }}" class="text-xs font-medium text-gray-500 hover:text-blue-600 transition-colors">
                        Password dimenticata?
                    </a>
                </div>

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

            <div class="flex items-center">
                <input 
                    id="remember" 
                    name="remember" 
                    type="checkbox" 
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer"
                >
                <label for="remember" class="ml-2 block text-sm text-gray-700 cursor-pointer select-none">
                    Ricordami
                </label>
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
                Entra
            </button>

            </form>
            
            <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                <p class="text-sm text-gray-600">
                    Non hai un account? 
                    <a href="{{ route('registrazione') }}" class="font-bold text-blue-600 hover:text-blue-800 hover:underline ml-1">
                        Registrati
                    </a>
                </p>
            </div>

        </div>
    </div>
@endsection