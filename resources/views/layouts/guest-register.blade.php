<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SPPG - Sistem Informasi Kepegawaian & Absensi</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen flex flex-col bg-slate-950 text-white antialiased selection:bg-blue-500/30">

    <!-- Animated Background Blobs - Fixed to stay in viewport -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-24 -left-20 w-96 h-96 bg-blue-600/20 rounded-full blur-[120px] animate-blob"></div>
        <div class="absolute -bottom-24 -right-20 w-96 h-96 bg-cyan-600/20 rounded-full blur-[120px] animate-blob [animation-delay:2s]"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-indigo-600/10 rounded-full blur-[150px] animate-pulse"></div>
    </div>

    <main class="relative z-10 flex-1 flex items-center justify-center p-4 sm:p-6 my-auto">
        @yield('content')
    </main>

    <footer class="relative z-10 text-center text-slate-500 text-xs py-4">
        &copy; {{ date('Y') }} SPPG Corporation. All rights reserved.
    </footer>

    @livewireScripts

    <style>
        @keyframes blob {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
        }
        .animate-blob { animation: blob 15s infinite alternate ease-in-out; }
    </style>
</body>
</html>