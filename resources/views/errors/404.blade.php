<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Stranica nije pronađena</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="text-center px-4">
        <p class="text-8xl font-bold text-indigo-600">404</p>
        <h1 class="mt-4 text-2xl font-semibold text-gray-800">Stranica nije pronađena</h1>
        <p class="mt-2 text-gray-500">Tražena stranica ne postoji ili je premještena.</p>
        <div class="mt-8 flex justify-center gap-4">
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('dashboard') }}"
               class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                &larr; Nazad
            </a>
            <a href="{{ route('dashboard') }}"
               class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                Na Dashboard
            </a>
        </div>
    </div>
</body>
</html>
