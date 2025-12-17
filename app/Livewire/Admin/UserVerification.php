<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;

class UserVerification extends Component
{
    public function verifyUser($userId)
    {
        $user = User::findOrFail($userId);

        $user->update([
            'email_verified_at' => now(),
        ]);

        session()->flash('success', 'Akun berhasil diverifikasi.');
    }

    public function deactivateUser($userId)
    {
        $user = User::findOrFail($userId);

        $user->update([
            'email_verified_at' => null,
        ]);

        session()->flash('success', 'Akun berhasil dinonaktifkan.');
    }

    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);

        // pengaman: admin tidak bisa hapus dirinya sendiri
        if ($user->id === auth()->id()) {
            session()->flash('error', 'Anda tidak bisa menghapus akun sendiri.');
            return;
        }

        $user->delete();

        session()->flash('success', 'Akun karyawan berhasil dihapus.');
    }

    public function render()
    {
        return view('livewire.admin.user-verification', [
            'users' => User::where('group', 'user')
                ->orderByRaw('email_verified_at IS NOT NULL')
                ->latest()
                ->get(),
        ]);
    }
}
