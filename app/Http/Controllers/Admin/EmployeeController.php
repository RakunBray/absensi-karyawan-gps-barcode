<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/admin/employees",
     *     tags={"Admin - Employees"},
     *     summary="Daftar karyawan",
     *     description="Menampilkan daftar semua karyawan",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function index()
    {
        $employees = User::where('group', 'user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.employees.index', compact('employees'));
    }

    /**
     * @OA\Post(
     *     path="/admin/employees/{id}/verify",
     *     tags={"Admin - Employees"},
     *     summary="Verifikasi akun karyawan",
     *     description="Mengaktifkan dan memverifikasi email karyawan",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID (ULID)",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=302,
     *         description="Redirect back dengan flash message"
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="User not found")
     * )
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
     * @OA\Post(
     *     path="/admin/employees/{id}/deactivate",
     *     tags={"Admin - Employees"},
     *     summary="Nonaktifkan akun karyawan",
     *     description="Menonaktifkan akun karyawan dengan menghapus email verification",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID (ULID)",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=302,
     *         description="Redirect back dengan flash message"
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="User not found")
     * )
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
     * @OA\Delete(
     *     path="/admin/employees/{id}",
     *     tags={"Admin - Employees"},
     *     summary="Hapus akun karyawan",
     *     description="Menghapus akun karyawan secara permanen. Tidak dapat menghapus admin atau akun sendiri.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID (ULID)",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=302,
     *         description="Redirect back dengan flash message"
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden - Cannot delete admin or self"),
     *     @OA\Response(response=404, description="User not found")
     * )
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
