{{-- resources/views/auth/forgot-password.blade.php --}}
@extends('layouts.guest-register')

@section('content')
<div class="w-full max-w-md mx-auto">
    <!-- Logo & Header -->
    <div class="flex flex-col items-center mb-4">
        <img src="/img/Logo-MBG.png" alt="Logo SPPG" class="h-20 w-auto mb-2 object-contain hover:scale-105 transition-transform">
        <h2 class="text-2xl font-bold tracking-tight text-white text-center">Lupa Kata Sandi?</h2>
        <p class="text-slate-400 text-sm mt-1 text-center px-4">Kami akan mengirimkan link reset ke email Anda.</p>
    </div>

    <!-- Main Card -->
    <div class="backdrop-blur-xl bg-white/5 border border-white/10 rounded-[2rem] shadow-2xl p-6 sm:p-8">
        <!-- Success Message -->
        @if (session('status'))
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl text-emerald-400 text-center text-xs font-medium">
                {{ session('status') }}
            </div>
        @endif

        <x-validation-errors class="mb-6 text-red-400 text-sm bg-red-500/10 p-3 rounded-xl border border-red-500/20 text-center" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf
            
            <div class="space-y-1.5">
                <label for="email" class="text-xs font-semibold text-slate-400 ml-2 uppercase tracking-wider">Email / Telepon</label>
                <div class="relative group">
                    <x-input 
                        type="text" 
                        name="email"
                        id="email"
                        :value="old('email')" 
                        required 
                        autofocus 
                        autocomplete="username"
                        class="w-full pl-6 pr-6 py-3.5 bg-slate-900/50 border {{ $errors->has('email') ? 'border-red-500/50' : 'border-white/10' }} rounded-2xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 focus:bg-slate-900 transition-all shadow-inner"
                        placeholder="Masukkan Email Terdaftar" 
                    />
                </div>
            </div>

            <button type="submit" class="w-full py-3.5 bg-blue-600 hover:bg-blue-500 active:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-900/20 transition-all hover:scale-[1.02] active:scale-[0.98] outline-none ring-offset-2 ring-offset-slate-950 focus:ring-2 focus:ring-blue-500">
                Kirim Link Reset
            </button>

            <div class="pt-2 text-center">
                <a href="{{ route('login') }}" class="text-slate-400 text-sm hover:text-white transition-colors flex items-center justify-center gap-2 group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Masuk
                </a>
            </div>
        </form>
    </div>
</div>
@endsection