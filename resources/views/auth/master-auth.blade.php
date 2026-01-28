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

<div id="app">
    <sidebar-provider>
        <app-sidebar variant="inset" :user='@json(auth()->user() ? ["name" => auth()->user()->name, "email" => auth()->user()->email] : null)'></app-sidebar>
        <sidebar-inset class="flex flex-1 flex-col min-w-0 overflow-hidden">
            <site-header></site-header>
            @yield('content-auth')
        </sidebar-inset>
    </sidebar-provider>
</div>

<script>
    console.log('Master auth loaded');
</script>
</body>
</html>