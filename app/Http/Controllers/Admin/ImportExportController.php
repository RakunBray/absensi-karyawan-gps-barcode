<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ImportExportController extends Controller
{
    /**
     * @OA\Get(
     *     path="/admin/import-export/users",
     *     tags={"Admin - Import/Export"},
     *     summary="Import/Export users",
     *     description="Menampilkan halaman untuk import dan export data users",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success - Returns HTML view"
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function users()
    {
        return view('admin.import-export.users');
    }

    /**
     * @OA\Get(
     *     path="/admin/import-export/attendances",
     *     tags={"Admin - Import/Export"},
     *     summary="Import/Export attendances",
     *     description="Menampilkan halaman untuk import dan export data absensi",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success - Returns HTML view"
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function attendances()
    {
        return view('admin.import-export.attendances');
    }
}
