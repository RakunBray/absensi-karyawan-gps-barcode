{{-- resources/views/auth/two-factor-challenge.blade.php --}}
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

            <!-- CARD 2FA — SAMA PERSIS DENGAN LOGIN & LAINNYA -->
            <div class="backdrop-blur-3xl bg-white/10 border border-white/30 rounded-3xl shadow-3xl p-10">

                <h2 class="text-4xl font-bold text-white text-center mb-3">Verifikasi Keamanan</h2>

                <div x-data="{ recovery: false }" class="space-y-8">

                    <!-- Pesan Utama -->
                    <div class="text-center text-purple-200 text-base leading-relaxed">
                        <p x-show="!recovery">
                            Masukkan kode autentikasi dari aplikasi authenticator Anda (Google Authenticator, Authy, dll).
                        </p>
                        <p x-cloak x-show="recovery">
                            Masukkan salah satu <strong>kode pemulihan darurat</strong> Anda.
                        </p>
                    </div>

                    <x-validation-errors class="text-red-400 text-center text-sm" />

                    <form method="POST" action="{{ route('two-factor.login') }}">
                        @csrf

                        <!-- Kode Authenticator -->
                        <div x-show="!recovery" class="space-y-4">
                            <div class="relative">
                                <x-input 
                                    id="code" 
                                    type="text" 
                                    inputmode="numeric" 
                                    name="code" 
                                    autofocus 
                                    x-ref="code"
                                    autocomplete="one-time-code"
                                    class="w-full px-8 py-5 text-center text-2xl tracking-widest bg-black/50 border {{ $errors->has('code') ? 'border-red-500' : 'border-white/30' }} rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-purple-500/60 focus:border-purple-400 transition shadow-xl"
                                    placeholder="—— ———" 
                                    maxlength="6"
                                />
                                @error('code') <p class="mt-3 text-red-400 text-center text-sm">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Recovery Code -->
                        <div x-cloak x-show="recovery" class="space-y-4">
                            <div class="relative">
                                <x-input 
                                    id="recovery_code" 
                                    type="text" 
                                    name="recovery_code" 
                                    x-ref="recovery_code"
                                    autocomplete="one-time-code"
                                    class="w-full px-8 py-5 text-center text-xl bg-black/50 border {{ $errors->has('recovery_code') ? 'border-red-500' : 'border-white/30' }} rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-purple-500/60 focus:border-purple-400 transition shadow-xl"
                                    placeholder="Masukkan Kode Pemulihan" 
                                />
                                @error('recovery_code') <p class="mt-3 text-red-400 text-center text-sm">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Tombol Switch & Login -->
                        <div class="flex flex-col items-center gap-6">

                            <!-- Switch Mode -->
                            <div class="text-sm">
                                <button type="button"
                                    x-show="!recovery"
                                    @click="recovery = true; $nextTick(() => $refs.recovery_code.focus())"
                                    class="text-purple-300 hover:text-white underline transition font-medium">
                                    Gunakan kode pemulihan
                                </button>

                                <button type="button"
                                    x-cloak x-show="recovery"
                                    @click="recovery = false; $nextTick(() => $refs.code.focus())"
                                    class="text-purple-300 hover:text-white underline transition font-medium">
                                    Gunakan kode authenticator
                                </button>
                            </div>

                            <!-- Tombol Login -->
                            <button type="submit"
                                class="w-full py-5 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-xl font-bold rounded-full shadow-2xl transition hover:scale-105">
                                Verifikasi & Masuk
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Kembali ke Login -->
                <div class="mt-8 text-center">
                    <a href="{{ route('login') }}" class="text-purple-300 hover:text-white underline text-base transition">
                        Kembali ke Masuk
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection