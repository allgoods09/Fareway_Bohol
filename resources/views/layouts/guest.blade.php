{{-- resources/views/layouts/guest.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Fareway Bohol') }} - Authentication</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased" style="font-family: 'Poppins', sans-serif;">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-gray-50 to-gray-100">
        <!-- Logo / Brand -->
        <div class="mb-6">
            <a href="/" class="flex items-center gap-3 group">
                <div class="w-12 h-12 bg-gradient-to-br from-[#1a2f4e] to-[#2a4f7a] rounded-xl flex items-center justify-center text-white text-xl shadow-md transition-transform group-hover:scale-105">
                    🚍
                </div>
                <div>
                    <span class="font-bold text-gray-800 text-xl tracking-tight">Fareway <span class="text-emerald-600">Bohol</span></span>
                </div>
            </a>
        </div>

        <!-- Auth Card -->
        <div class="w-full sm:max-w-md px-6 py-8 bg-white shadow-lg rounded-2xl border border-gray-100">
            {{ $slot }}
        </div>

        <!-- Footer Link -->
        <div class="mt-6 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} Fareway Bohol — Public Transport Navigator
        </div>
    </div>
</body>
</html>