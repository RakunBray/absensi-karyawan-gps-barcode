{{-- resources/views/auth/verify-email.blade.php --}}
@extends('layouts.guest-register')

@section('content')
<div class="w-full max-w-md mx-auto">
    <!-- Logo & Header -->
    <div class="flex flex-col items-center mb-4">
        <img src="/img/Logo-MBG.png" alt="Logo SPPG" class="h-20 w-auto mb-2 object-contain hover:scale-105 transition-transform">
        <h2 class="text-2xl font-bold tracking-tight text-white text-center">Verifikasi Email</h2>
        <p class="text-slate-400 text-sm mt-1">Satu langkah lagi untuk memulai</p>
    </div>

    <!-- Main Card -->
    <div class="backdrop-blur-xl bg-white/5 border border-white/10 rounded-[2rem] shadow-2xl p-6 sm:p-8">
        <p class="text-slate-300 text-sm mb-6 leading-relaxed text-center">
            Sebelum melanjutkan, mohon verifikasi alamat email Anda dengan mengklik link yang telah kami kirimkan ke email Anda.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-6 p-4 bg-green-500/10 border border-green-500/20 rounded-xl text-green-400 text-center text-xs font-medium">
                Link verifikasi baru telah dikirim ke email Anda!
            </div>
        @endif

        <x-validation-errors class="mb-6 text-red-400 text-sm text-center" />

        <div class="mt-4 flex flex-col gap-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full py-3.5 bg-blue-600 hover:bg-blue-500 active:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-900/20 transition-all hover:scale-[1.02] active:scale-[0.98] outline-none ring-offset-2 ring-offset-slate-950 focus:ring-2 focus:ring-blue-500">
                    Kirim Ulang Email Verifikasi
                </button>
            </form>

            <div class="flex items-center justify-between mt-2 px-2 text-sm">
                <a href="{{ route('profile.show') }}" class="text-slate-400 hover:text-white transition-colors">
                    Edit Profil
                </a>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-blue-400 hover:text-blue-300 font-medium transition-colors">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection