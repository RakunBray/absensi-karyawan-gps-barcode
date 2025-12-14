{{-- resources/views/auth/verify-email.blade.php --}}
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

            <!-- CARD VERIFIKASI EMAIL — SAMA PERSIS DENGAN LOGIN & LAINNYA -->
            <div class="backdrop-blur-3xl bg-white/10 border border-white/30 rounded-3xl shadow-3xl p-10">

                <h2 class="text-4xl font-bold text-white text-center mb-3">Verifikasi Email</h2>
                <p class="text-center text-purple-200 text-base mb-10 leading-relaxed">
                    Sebelum melanjutkan, mohon verifikasi alamat email Anda dengan mengklik link yang telah kami kirimkan ke email Anda.
                </p>

                <!-- Success Message -->
                @if (session('status') == 'verification-link-sent')
                    <div class="mb-8 p-5 bg-green-900/50 border border-green-500/50 rounded-2xl text-green-200 text-center text-sm font-medium">
                        Link verifikasi baru telah dikirim ke email Anda!
                    </div>
                @endif

                <x-validation-errors class="mb-8 text-red-400 text-center text-sm" />

                <!-- Tombol Kirim Ulang -->
                <div class="text-center mb-10">
                    <form method="POST" action="{{ route('verification.send') }}" class="inline">
                        @csrf
                        <button type="submit"
                            class="px-12 py-4 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-lg font-bold rounded-full shadow-xl transition hover:scale-105">
                            Kirim Ulang Email Verifikasi
                        </button>
                    </form>
                </div>

                <!-- Aksi Tambahan -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-6 text-sm">
                    <a href="{{ route('profile.show') }}"
                        class="text-purple-300 hover:text-white underline transition font-medium">
                        Edit Profil
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                            class="text-purple-300 hover:text-white underline transition font-medium">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection