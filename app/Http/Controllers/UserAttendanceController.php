<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class UserAttendanceController extends Controller
{
    /**
     * @OA\Get(
     *     path="/apply-leave",
     *     tags={"User - Attendance"},
     *     summary="Form pengajuan cuti/izin",
     *     description="Menampilkan form untuk pengajuan cuti atau izin karyawan",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success - Returns HTML view"
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden - Account not verified")
     * )
     */
    public function applyLeave()
    {
        // ❌ BLOK USER BELUM DIVERIFIKASI
        if (is_null(Auth::user()->email_verified_at)) {
            abort(403, 'Akun Anda belum diverifikasi oleh admin.');
        }

        $attendance = Attendance::where('user_id', Auth::user()->id)
            ->where('date', date('Y-m-d'))
            ->first();

        return view('attendances.apply-leave', compact('attendance'));
    }

    /**
     * @OA\Post(
     *     path="/apply-leave",
     *     tags={"User - Attendance"},
     *     summary="Submit pengajuan cuti/izin",
     *     description="Menyimpan pengajuan cuti atau izin karyawan",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"status", "note", "from"},
     *                 @OA\Property(property="status", type="string", enum={"excused", "sick"}, description="Jenis izin"),
     *                 @OA\Property(property="note", type="string", maxLength=255, description="Keterangan"),
     *                 @OA\Property(property="from", type="string", format="date", description="Tanggal mulai"),
     *                 @OA\Property(property="to", type="string", format="date", description="Tanggal selesai (opsional)"),
     *                 @OA\Property(property="attachment", type="string", format="binary", description="File lampiran (max 3MB)"),
     *                 @OA\Property(property="lat", type="number", format="double", description="Latitude lokasi"),
     *                 @OA\Property(property="lng", type="number", format="double", description="Longitude lokasi")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=302,
     *         description="Redirect to home dengan flash message"
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden - Account not verified"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function storeLeaveRequest(Request $request)
    {
        // ❌ BLOK USER BELUM DIVERIFIKASI
        if (is_null(Auth::user()->email_verified_at)) {
            return redirect()->back()
                ->with('flash.banner', 'Akun Anda belum diverifikasi oleh admin.')
                ->with('flash.bannerStyle', 'danger');
        }

        $request->validate([
            'status' => ['required', 'in:excused,sick'],
            'note' => ['required', 'string', 'max:255'],
            'from' => ['required', 'date'],
            'to' => ['nullable', 'date', 'after:from'],
            'attachment' => ['nullable', 'file', 'max:3072'],
            'lat' => ['nullable', 'numeric'],
            'lng' => ['nullable', 'numeric'],
        ]);

        try {
            $newAttachment = null;

            if ($request->file('attachment')) {
                $newAttachment = $request->file('attachment')->storePublicly(
                    'attachments',
                    ['disk' => config('jetstream.attachment_disk')]
                );
            }

            $fromDate = Carbon::parse($request->from);
            $toDate   = Carbon::parse($request->to ?? $fromDate);

            $fromDate->range($toDate)->forEach(function (Carbon $date) use ($request, $newAttachment) {
                Attendance::updateOrCreate(
                    [
                        'user_id' => Auth::id(),
                        'date'    => $date->format('Y-m-d'),
                    ],
                    [
                        'status'     => $request->status,
                        'note'       => $request->note,
                        'attachment' => $newAttachment,
                        'latitude'   => $request->lat ? (float) $request->lat : null,
                        'longitude'  => $request->lng ? (float) $request->lng : null,
                    ]
                );
            });

            Attendance::clearUserAttendanceCache(Auth::user(), $fromDate);

            if (!$fromDate->isSameMonth($toDate)) {
                Attendance::clearUserAttendanceCache(Auth::user(), $toDate);
            }

            return redirect()->route('home')
                ->with('flash.banner', 'Pengajuan berhasil disimpan.');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('flash.banner', $th->getMessage())
                ->with('flash.bannerStyle', 'danger');
        }
    }

    /**
     * @OA\Get(
     *     path="/attendance-history",
     *     tags={"User - Attendance"},
     *     summary="Riwayat absensi",
     *     description="Menampilkan riwayat absensi karyawan",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success - Returns HTML view"
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function history()
    {
        return view('attendances.history');
    }
}
