{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.guest-register')

@section('content')
<div class="w-full max-w-lg py-12">

        <div class="flex justify-center mb-10">
            <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-green-500 rounded-3xl flex items-center justify-center text-6xl font-bold text-white shadow-2xl ring-12 ring-blue-500/30">
                <img src="/img/MBG.png"> 
            </div>
        </div>

        <div class="backdrop-blur-3xl bg-white/10 border border-white/30 rounded-3xl shadow-3xl p-10 flex-grow">

            <h2 class="text-4xl font-bold text-white text-center mb-3">Selamat Datang</h2>
            <p class="text-center text-blue-200 text-base mb-12">Masuk ke akun Absensi SPPG Anda</p>

            <x-validation-errors class="mb-8 text-red-400 text-center text-sm" />

            <form method="POST" action="{{ route('login') }}" class="flex flex-col h-full">
                @csrf
                <div class="mb-8">
                    <x-input 
                        type="text" 
                        name="email"
                        :value="old('email')" 
                        required 
                        autofocus 
                        autocomplete="username"
                        class="w-full px-8 py-5 bg-black/50 border {{ $errors->has('email') ? 'border-red-500' : 'border-white/30' }} rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-blue-500/60 focus:border-blue-400 transition text-lg shadow-xl text-center"
                        placeholder="Email atau Nomor Telepon" 
                    />
                </div>

                <div class="relative mb-10">
                    <x-input 
                        type="password" 
                        name="password" 
                        id="password"
                        required 
                        autocomplete="current-password"
                        class="w-full px-8 py-5 pr-16 bg-black/50 border {{ $errors->has('password') ? 'border-red-500' : 'border-white/30' }} rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-blue-500/60 focus:border-blue-400 transition text-lg shadow-xl text-center"
                        placeholder="Kata Sandi" 
                    />
                    <button type="button" onclick="togglePass(this)" class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-300 hover:text-white focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" id="eye-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>

                <div class="flex items-center justify-between mb-10 text-base">
                    <label class="flex items-center text-gray-300">
                        <x-checkbox name="remember" />
                        <span class="ml-3">Ingat saya</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-blue-300 hover:text-white underline">
                        Lupa kata sandi?
                    </a>
                </div>

                <button type="submit" class="w-full py-5 bg-gradient-to-r from-blue-600 to-green-600 hover:from-blue-700 hover:to-green-700 text-white text-xl font-bold rounded-full shadow-2xl transition hover:scale-105 mb-10">
                    Masuk Sekarang
                </button>

                <div class="mt-auto text-center">
                    <p class="text-gray-400 text-base">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="text-blue-300 font-bold hover:text-white underline">Daftar di sini</a>
                    </p>
                </div>
            </form>
        </div>
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