<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): \Inertia\Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Autentikasi default Breeze
        $request->authenticate();

        // Dapatkan user setelah attempt sukses
        $user = Auth::user();

        // Check verifikasi status, tapi bypass untuk admin/superadmin
        if ($user->status !== 'approved' && ! $user->is_admin) {  // Gunakan accessor is_admin dari User model-mu
            Auth::logout();  // Logout kalau gagal verifikasi
            throw ValidationException::withMessages([
                'email' => __('Akun Anda belum diverifikasi oleh admin.'),
            ]);
        }

        // Regenerate session kalau lolos
        $request->session()->regenerate();

        // Redirect ke dashboard atau home
        return redirect()->intended('/dashboard');  // Ganti '/dashboard' dengan route home-mu
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}