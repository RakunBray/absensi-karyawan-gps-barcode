<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class MasterDataController extends Controller
{
    /**
     * @OA\Get(
     *     path="/admin/masterdata/division",
     *     tags={"Admin - Master Data"},
     *     summary="Master data divisi",
     *     description="Menampilkan halaman manajemen master data divisi",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success - Returns HTML view"
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function division()
    {
        return view('admin.master-data.division');
    }

    /**
     * @OA\Get(
     *     path="/admin/masterdata/job-title",
     *     tags={"Admin - Master Data"},
     *     summary="Master data jabatan",
     *     description="Menampilkan halaman manajemen master data jabatan",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success - Returns HTML view"
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function jobTitle()
    {
        return view('admin.master-data.job-title');
    }

    /**
     * @OA\Get(
     *     path="/admin/masterdata/education",
     *     tags={"Admin - Master Data"},
     *     summary="Master data pendidikan",
     *     description="Menampilkan halaman manajemen master data pendidikan",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success - Returns HTML view"
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function education()
    {
        return view('admin.master-data.education');
    }

    /**
     * @OA\Get(
     *     path="/admin/masterdata/shift",
     *     tags={"Admin - Master Data"},
     *     summary="Master data shift",
     *     description="Menampilkan halaman manajemen master data shift kerja",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success - Returns HTML view"
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function shift()
    {
        return view('admin.master-data.shift');
    }

    /**
     * @OA\Get(
     *     path="/admin/masterdata/admin",
     *     tags={"Admin - Master Data"},
     *     summary="Master data admin",
     *     description="Menampilkan halaman manajemen master data admin",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success - Returns HTML view"
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function admin()
    {
        return view('admin.master-data.admin');
    }
}
