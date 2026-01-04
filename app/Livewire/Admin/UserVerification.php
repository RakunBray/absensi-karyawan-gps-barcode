<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;

class UserVerification extends Component
{
    public function approveUser($userId)
    {
        $user = User::findOrFail($userId);

        $user->update([
            'status' => 'approved',
        ]);

        // Kirim email verifikasi setelah approved
        $user->sendEmailVerificationNotification();

        session()->flash('success', 'Akun berhasil disetujui dan email verifikasi dikirim.');
    }

    public function rejectUser($userId)
    {
        $user = User::findOrFail($userId);

        $user->update([
            'status' => 'rejected',
        ]);

        session()->flash('success', 'Akun berhasil ditolak.');
    }

    public function verifyUser($userId)
    {
        $user = User::findOrFail($userId);

        $user->update([
            'email_verified_at' => now(),
        ]);

        session()->flash('success', 'Email berhasil diverifikasi.');
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
            'pendingUsers' => User::where('status', 'pending')->latest()->get(),
            'approvedUsers' => User::where('status', 'approved')->latest()->get(),
            'rejectedUsers' => User::where('status', 'rejected')->latest()->get(),
        ]);
    }
}
