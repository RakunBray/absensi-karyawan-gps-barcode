{{-- resources/views/auth/passwords/email.blade.php --}}
@extends('layouts.guest-register')

@section('content')
    <div class="min-h-screen flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-lg">

            <!-- Logo -->
            <div class="flex justify-center mb-10">
                <div class="w-24 h-24 bg-gradient-to-br from-purple-500 to-pink-500 rounded-3xl flex items-center justify-center text-6xl font-bold text-white shadow-2xl ring-12 ring-purple-500/30">
                    M
                </div>
            </div>

            <!-- CARD LUPA KATA SANDI — SAMA PERSIS DENGAN LOGIN & REGISTER -->
            <div class="backdrop-blur-3xl bg-white/10 border border-white/30 rounded-3xl shadow-3xl p-10">

                <h2 class="text-4xl font-bold text-white text-center mb-3">Lupa Kata Sandi?</h2>
                <p class="text-center text-purple-200 text-base mb-10 leading-relaxed">
                    Tenang! Masukkan email atau nomor telepon yang terdaftar, kami akan kirim link untuk reset kata sandi Anda.
                </p>

                <!-- Success Message -->
                @if (session('status'))
                    <div class="mb-8 p-5 bg-green-900/50 border border-green-500/50 rounded-2xl text-green-200 text-center text-sm font-medium">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Validation Errors -->
                <x-validation-errors class="mb-8 text-red-400 text-center text-sm" />

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <!-- Email / Nomor Telepon -->
                    <div class="mb-10">
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
                        @error('email')
                            <p class="mt-3 text-red-400 text-center text-sm font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tombol Kirim Link -->
                    <button type="submit"
                        class="w-full py-5 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-xl font-bold rounded-full shadow-2xl transition hover:scale-105">
                        Kirim Link Reset Kata Sandi
                    </button>

                    <!-- Kembali ke Login -->
                    <div class="mt-8 text-center">
                        <a href="{{ route('login') }}" class="text-purple-300 hover:text-white underline text-base font-medium transition">
                            Kembali ke Masuk
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection