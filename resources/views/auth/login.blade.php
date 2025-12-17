{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.guest-register')

@section('content')
<div class="min-h-screen flex items-center justify-center px-6 py-12">
    <div class="w-full max-w-lg">

        <div class="flex justify-center mb-10">
            <div class="w-24 h-24 bg-gradient-to-br from-purple-500 to-pink-500 rounded-3xl flex items-center justify-center text-6xl font-bold text-white shadow-2xl ring-12 ring-purple-500/30">
                <img src="/Public/img/MBG.png"> 
            </div>
        </div>

        <div class="backdrop-blur-3xl bg-white/10 border border-white/30 rounded-3xl shadow-3xl p-10">

            <h2 class="text-4xl font-bold text-white text-center mb-3">Selamat Datang</h2>
            <p class="text-center text-purple-200 text-base mb-12">Masuk ke akun Absensi MBG Anda</p>

            <x-validation-errors class="mb-8 text-red-400 text-center text-sm" />

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-8">
                    <x-input 
                        type="text" 
                        name="email"
                        :value="old('email')" 
                        required 
                        autofocus 
                        autocomplete="username"
                        class="w-full px-8 py-5 bg-black/50 border {{ $errors->has('email') ? 'border-red-500' : 'border-white/30' }} rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-purple-500/60 focus:border-purple-400 transition text-lg shadow-xl text-center"
                        placeholder="Email atau Nomor Telepon" 
                    />
                </div>

                <div class="relative mb-10">
                    <x-input 
                        type="password" 
                        name="password" 
                        required 
                        autocomplete="current-password"
                        class="w-full px-8 py-5 pr-16 bg-black/50 border {{ $errors->has('password') ? 'border-red-500' : 'border-white/30' }} rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-purple-500/60 focus:border-purple-400 transition text-lg shadow-xl text-center"
                        placeholder="Kata Sandi" 
                    />
                    <button type="button" onclick="togglePass('password', this)" class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-300 hover:text-white">
                        <!-- eye icon -->
                    </button>
                </div>

                <div class="flex items-center justify-between mb-10 text-base">
                    <label class="flex items-center text-gray-300">
                        <x-checkbox name="remember" />
                        <span class="ml-3">Ingat saya</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-purple-300 hover:text-white underline">
                        Lupa kata sandi?
                    </a>
                </div>

                <button type="submit" class="w-full py-5 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-xl font-bold rounded-full shadow-2xl transition hover:scale-105">
                    Masuk Sekarang
                </button>

                <div class="mt-10 text-center">
                    <p class="text-gray-400 text-base">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="text-purple-300 font-bold hover:text-white underline">Daftar di sini</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

{{--<script>
function togglePass(id, btn) {
    const x = document.getElementById(id);
    x.type = x.type === "password" ? "text" : "password";
}
</script>--}}
    <script>
        function togglePass(fieldId, button) {
            const input = document.getElementById(fieldId);
            const eyeOpen = button.querySelector(`#eye-open-${fieldId}`);
            const eyeClosed = button.querySelector(`#eye-closed-${fieldId}`);
            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            } else {
                input.type = 'password';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            }
        }
    </script>
@endsection