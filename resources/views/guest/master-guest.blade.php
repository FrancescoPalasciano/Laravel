<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Il mio Sito Laravel')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex flex-col min-h-screen bg-gray-100">

    <!-- includo header e footer -->
    @include('guest.partials.header')

    <main class="grow container mx-auto px-6 py-8">
        @yield('content-guest')
    </main>

    @include('guest.partials.footer')

</body>
</html>