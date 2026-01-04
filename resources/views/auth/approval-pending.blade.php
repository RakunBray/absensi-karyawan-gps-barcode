@extends('layouts.guest-register')

@section('content')
<div class="w-full max-w-md mx-auto">
    <!-- Logo & Header -->
    <div class="flex flex-col items-center mb-4">
        <img src="/img/Logo-MBG.png" alt="Logo SPPG" class="h-20 w-auto mb-2 object-contain hover:scale-105 transition-transform">
        <h2 class="text-2xl font-bold tracking-tight text-white text-center">Menunggu Persetujuan</h2>
        <p class="text-slate-400 text-sm mt-1">Akun Anda sedang ditinjau</p>
    </div>

    <!-- Main Card -->
    <div class="backdrop-blur-xl bg-white/5 border border-white/10 rounded-[2rem] shadow-2xl p-6 sm:p-8">
        <div class="mb-6 text-center text-slate-300 text-sm leading-relaxed">
            Terima kasih telah mendaftar! Akun Anda saat ini sedang dalam peninjauan oleh administrator. Mohon tunggu persetujuan sebelum Anda dapat mengakses sistem.
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-6 p-4 bg-green-500/10 border border-green-500/20 rounded-xl text-green-400 text-center text-xs font-medium">
                {{ __('Tautan verifikasi baru telah dikirim ke alamat email yang Anda berikan di pengaturan profil Anda.') }}
            </div>
        @endif

        @if (Auth::user()->status === 'rejected')
             <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-xl text-red-400 text-center text-xs font-medium">
                {{ __('Maaf, pendaftaran akun Anda ditolak. Silakan hubungi admin untuk informasi lebih lanjut.') }}
            </div>
        @endif

        <div class="mt-6 flex justify-center">
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-blue-400 hover:text-blue-300 font-medium transition-colors text-sm">
                    {{ __('Keluar') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
