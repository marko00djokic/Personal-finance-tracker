<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 — Greška servera</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="text-center px-4">
        <p class="text-8xl font-bold text-red-500">500</p>
        <h1 class="mt-4 text-2xl font-semibold text-gray-800">Greška servera</h1>
        <p class="mt-2 text-gray-500">Nešto je pošlo po zlu na našoj strani. Pokušajte ponovo za nekoliko trenutaka.</p>
        <div class="mt-8 flex justify-center gap-4">
            <a href="{{ route('dashboard') }}"
               class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                Na Dashboard
            </a>
        </div>
    </div>
</body>
</html>
