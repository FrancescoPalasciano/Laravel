<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Il mio Sito Laravel')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex flex-col min-h-screen bg-gray-100">
    <div id="page-loader" class="fixed inset-0 flex items-center justify-center bg-white transition-opacity duration-500">
        <div class="w-12 h-12 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
    </div>

    <main class="grow container mx-auto px-6 py-8">
        @yield('content-guest')
    </main>

</body>
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
</html>