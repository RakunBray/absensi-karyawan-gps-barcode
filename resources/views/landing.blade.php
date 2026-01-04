<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPPG - Sistem Informasi Kepegawaian & Absensi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gradient-to-br from-blue-900 via-blue-800 to-blue-900 text-white min-h-screen">

    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-50 backdrop-blur-xl bg-black/40 border-b border-white/10">
        <div class="max-w-7xl mx-auto px-6 py-5 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-green-500 rounded-xl flex items-center justify-center text-2xl font-bold shadow-2xl ring-4 ring-blue-500/30">
                    <img src="/img/MBG.png" alt="Logo SPPG" class="w-10 h-10 rounded-xl">
                </div>
                <span class="text-2xl font-bold">SPPG Corporation</span>
            </div>
            <div class="flex items-center space-x-6">
               {{-- <a href="#fitur" class="hover:text-purple-400 transition">Fitur</a>
                <a href="#tentang" class="hover:text-purple-400 transition">Tentang</a>--}}
                <a href="{{ route('register') }}" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-green-600 hover:from-blue-700 hover:to-green-700 rounded-full font-semibold transition transform hover:scale-105">
                    Daftar Akun Absensi
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="pt-32 pb-24 text-center px-6">
        <div class="max-w-5xl mx-auto">
            <h1 class="text-5xl md:text-7xl font-extrabold leading-tight mb-6">
                Sistem Absensi & Kepegawaian<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-green-400">SPPG Corporation</span>
            </h1>
            <p class="text-xl md:text-2xl text-gray-300 mb-12 max-w-3xl mx-auto">
                Kelola absensi, cuti, data karyawan, dan laporan secara digital — cepat, akurat, dan aman.
            </p>
            <div class="flex flex-col sm:flex-row gap-6 justify-center">
                <a href="{{ route('login') }}" class="px-12 py-5 bg-gradient-to-r from-blue-600 to-green-600 hover:from-blue-700 hover:to-green-700 text-xl font-bold rounded-full shadow-2xl transform hover:scale-105 transition">
                    Masuk Sekarang
                </a>
                {{--<a href="#fitur" class="px-12 py-5 border-2 border-white/40 hover:bg-white/10 text-xl font-bold rounded-full backdrop-blur transition">
                    Lihat Fitur
                </a>--}}
            </div>
        </div>
    </section>

    <!-- Fitur Singkat -->
    <section id="fitur" class="py-20 px-6">
        <div class="max-w-6xl mx-auto text-center">
            <h2 class="text-4xl md:text-5xl font-bold mb-16">Fitur Utama</h2>
            <div class="grid md:grid-cols-3 gap-10">
                @foreach([
                    ['Absen Real-time', 'Absen masuk/pulang hanya dengan satu klik'],
                    ['Absensi GPS', 'Lokasi akurat dengan GPS untuk validasi absensi'],
                    ['Barcode Scan', 'Scan barcode untuk absensi cepat dan aman'],
                    ['Laporan Otomatis', 'Rekap bulanan, export Excel & PDF'],
                    ['Manajemen Karyawan', 'Data lengkap + QR Code karyawan'],
                    ['Hak Akses Role', 'Admin & User terpisah dengan aman']
                ] as $fitur)
                    <div class="backdrop-blur-md bg-white/5 border border-white/10 rounded-2xl p-8 hover:bg-white/10 transition">
                        <h3 class="text-2xl font-bold mb-3">{{ $fitur[0] }}</h3>
                        <p class="text-gray-300">{{ $fitur[1] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 text-center border-t border-white/10">
        <p class="text-lg">© {{ date('Y') }} <strong>SPPG Corporation</strong>. All rights reserved.</p>
        <p class="text-sm text-gray-400 mt-2">
            Dibangun & dikelola oleh <span class="text-blue-400 font-bold">Kamu</span>
        </p>
    </footer>
</body>
</html>