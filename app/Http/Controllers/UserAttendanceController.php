<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class UserAttendanceController extends Controller
{
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

    public function history()
    {
        return view('attendances.history');
    }
}
