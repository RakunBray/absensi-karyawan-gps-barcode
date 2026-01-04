<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/home",
     *     tags={"User - Dashboard"},
     *     summary="Dashboard user",
     *     description="Menampilkan dashboard utama untuk karyawan",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success - Returns HTML view"
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function __invoke(Request $request)
    {
        return view('home');
    }
}
