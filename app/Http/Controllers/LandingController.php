<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LandingController extends Controller
{
    public function index()
    {
        // Jika user sudah login → langsung arahkan ke dashboard sesuai role
        if (Auth::check()) {
            return redirect()->route(Auth::user()->isAdmin ? 'admin.dashboard' : 'home');
        }

        // Jika belum login → tampilkan landing page
        return view('landing');
    }
}