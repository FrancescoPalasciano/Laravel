@extends('guest.master-login')

@section('title', 'Reimposta Password')

@section('content-guest')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-sm border border-gray-100">
        
        <h1 class="text-2xl font-bold text-blue-600 text-center mb-6">Nuova Password</h1>

        <form action="{{ route('password.update') }}" method="POST" class="space-y-6">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" name="email" value="{{ $email ?? old('email') }}" readonly
                    class="w-full px-4 py-3 rounded-lg bg-gray-100 border border-gray-300 text-gray-500 cursor-not-allowed">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Nuova Password</label>
                <input type="password" name="password" required autofocus
                    class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 focus:ring-2 focus:ring-blue-500 transition duration-200">
                @error('password')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Conferma Password</label>
                <input type="password" name="password_confirmation" required
                    class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 focus:ring-2 focus:ring-blue-500 transition duration-200">
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-lg transition duration-300">
                Salva Nuova Password
            </button>
        </form>
    </div>
</div>
@endsection