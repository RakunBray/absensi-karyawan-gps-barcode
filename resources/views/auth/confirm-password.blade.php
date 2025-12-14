{{-- resources/views/auth/confirm-password.blade.php --}}
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

            <!-- CARD KONFIRMASI KATA SANDI — SAMA PERSIS DENGAN LOGIN -->
            <div class="backdrop-blur-3xl bg-white/10 border border-white/30 rounded-3xl shadow-3xl p-10">

                <h2 class="text-4xl font-bold text-white text-center mb-3">Konfirmasi Kata Sandi</h2>
                <p class="text-center text-purple-200 text-base mb-10 leading-relaxed">
                    Ini adalah area aman. Silakan masukkan kata sandi Anda untuk melanjutkan.
                </p>

                <x-validation-errors class="mb-8 text-red-400 text-center text-sm" />

                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    <!-- Password + Eye Icon -->
                    <div class="relative mb-12">
                        <x-input 
                            type="password" 
                            name="password" 
                            id="password-confirm" 
                            required 
                            autofocus 
                            autocomplete="current-password"
                            class="w-full px-8 py-5 pr-16 bg-black/50 border {{ $errors->has('password') ? 'border-red-500' : 'border-white/30' }} rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-purple-500/60 focus:border-purple-400 transition text-lg shadow-xl text-center"
                            placeholder="Masukkan Kata Sandi Anda" 
                        />
                        <button type="button" onclick="togglePass('password-confirm', this)"
                            class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-300 hover:text-white transition z-10">
                            <svg class="w-7 h-7 hidden" id="eye-open-password-confirm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg class="w-7 h-7" id="eye-closed-password-confirm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                        @error('password')
                            <p class="mt-3 text-red-400 text-center text-sm font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tombol Konfirmasi -->
                    <button type="submit"
                        class="w-full py-5 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-xl font-bold rounded-full shadow-2xl transition hover:scale-105">
                        Konfirmasi & Lanjutkan
                    </button>

                    <!-- Kembali -->
                    <div class="mt-8 text-center">
                        <a href="{{ url()->previous() }}" class="text-purple-300 hover:text-white underline text-base transition">
                            Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script Toggle Password -->
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