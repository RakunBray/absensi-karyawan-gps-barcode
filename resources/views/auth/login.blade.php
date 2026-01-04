{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.guest-register')

@section('content')
<div class="w-full max-w-md mx-auto">
    <!-- Logo & Header -->
    <div class="flex flex-col items-center mb-4">
        <img src="/img/Logo-MBG.png" alt="Logo SPPG" class="h-20 w-auto mb-2 object-contain hover:scale-105 transition-transform">
        <h2 class="text-2xl font-bold tracking-tight text-white text-center">Selamat Datang</h2>
        <p class="text-slate-400 text-sm">Masuk ke akun Absensi SPPG Anda</p>
    </div>

    <!-- Main Card -->
    <div class="backdrop-blur-xl bg-white/5 border border-white/10 rounded-[2rem] shadow-2xl p-6 sm:p-8">
        <x-validation-errors class="mb-4 text-red-400 text-sm bg-red-500/10 p-3 rounded-xl border border-red-500/20" />

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
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
                        placeholder="Email atau Nomor Telepon" 
                    />
                </div>
            </div>

            <div class="space-y-1.5">
                <div class="flex items-center justify-between ml-2">
                    <label for="password" class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kata Sandi</label>
                    <a href="{{ route('password.request') }}" class="text-xs font-medium text-blue-400 hover:text-blue-300 transition-colors">
                        Lupa?
                    </a>
                </div>
                <div class="relative group">
                    <x-input 
                        type="password" 
                        name="password" 
                        id="password"
                        required 
                        autocomplete="current-password"
                        class="w-full pl-6 pr-14 py-3.5 bg-slate-900/50 border {{ $errors->has('password') ? 'border-red-500/50' : 'border-white/10' }} rounded-2xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 focus:bg-slate-900 transition-all shadow-inner"
                        placeholder="••••••••" 
                    />
                    <button type="button" onclick="togglePass(this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 focus:outline-none transition-colors p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" id="eye-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex items-center">
                <label class="flex items-center cursor-pointer group">
                    <x-checkbox name="remember" class="w-4 h-4 rounded border-white/10 bg-slate-900 text-blue-600 focus:ring-blue-500/50 focus:ring-offset-slate-900" />
                    <span class="ml-2.5 text-sm text-slate-400 group-hover:text-slate-300 transition-colors">Ingat saya</span>
                </label>
            </div>

            <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-500 active:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-900/20 transition-all hover:scale-[1.02] active:scale-[0.98] outline-none ring-offset-2 ring-offset-slate-950 focus:ring-2 focus:ring-blue-500 mt-2">
                Masuk Sekarang
            </button>

            <div class="pt-2 text-center">
                <p class="text-slate-400 text-sm">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-blue-400 font-semibold hover:text-blue-300 transition-colors">Daftar di sini</a>
                </p>
            </div>
        </form>
    </div>
</div>

<script>
function togglePass(btn) {
    const input = document.getElementById('password');
    const icon = btn.querySelector('#eye-icon');

    if (input.type === "password") {
        input.type = "text";
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
        `;
    } else {
        input.type = "password";
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        `;
    }
}
</script>
@endsection