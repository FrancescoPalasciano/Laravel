<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Il mio Sito Laravel')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex flex-col min-h-screen bg-gray-100">
    <div id="page-loader" class="fixed inset-0 flex items-center justify-center bg-white transition-opacity duration-500">
        <div class="w-12 h-12 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
    </div>

    <div id="app">
        <sidebar-provider>
            <app-sidebar variant="inset" :user='@json(auth()->user() ? ["name" => auth()->user()->name, "email" => auth()->user()->email] : null)'></app-sidebar>
            <sidebar-inset class="flex flex-1 flex-col min-w-0 overflow-hidden">
                <site-header></site-header>
                @yield('content-auth')
            </sidebar-inset>
        </sidebar-provider>
    </div>
<!-- TASK -->
<!-- tabella -->
<!-- inserire il create -->
<!-- spostare i validator -->
<!-- modifica alert -->
<!-- disattivazione utente -->
<!-- indentare il codice BENE -QUASI FATTO -->
<!-- spostare le crude utente nell'user controller -FATTO-->
<!-- sistemare le rotte e reindirizzamenti -FATTO-->
<!-- sistemnare regex numero -FATTO-->
<style>
    /* Evita lo scroll mentre carica */
    body.loading { overflow: hidden; }
</style>

<script>
    window.addEventListener('load', function() {
        const loader = document.getElementById('page-loader');
        loader.style.opacity = '0';
        setTimeout(() => {
            loader.style.display = 'none';
            document.body.classList.remove('loading');
        }, 500);
    });
</script>
</body>
</html>