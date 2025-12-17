<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    /**
     * Daftar karyawan
     */
    public function index()
    {
        $employees = User::where('group', 'user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.employees.index', compact('employees'));
    }

    /**
     * Verifikasi akun karyawan
     */
    public function verify(string $id): RedirectResponse
    {
        $user = User::where('group', 'user')->findOrFail($id);

        if ($user->email_verified_at) {
            return back()->with(
                'flash.banner',
                'Akun ini sudah diverifikasi.'
            );
        }

        $user->update([
            'email_verified_at' => now(),
        ]);

        return back()->with(
            'flash.banner',
            'Akun karyawan berhasil diverifikasi.'
        );
    }

    /**
     * Nonaktifkan akun karyawan
     */
    public function deactivate(string $id): RedirectResponse
    {
        $user = User::where('group', 'user')->findOrFail($id);

        $user->update([
            'email_verified_at' => null,
        ]);

        return back()->with(
            'flash.banner',
            'Akun karyawan berhasil dinonaktifkan.'
        );
    }

    /**
     * Hapus akun karyawan
     */
    public function destroy(string $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        // ❌ Cegah hapus admin
        if (in_array($user->group, ['admin', 'superadmin'])) {
            abort(403, 'Admin tidak boleh dihapus.');
        }

        // ❌ Cegah hapus diri sendiri
        if ($user->id === Auth::id()) {
            abort(403, 'Tidak boleh menghapus akun sendiri.');
        }

        $user->delete();

        return back()->with(
            'flash.banner',
            'Akun karyawan berhasil dihapus.'
        );
    }
}
