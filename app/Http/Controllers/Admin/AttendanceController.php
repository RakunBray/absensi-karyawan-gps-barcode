<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attendance;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class AttendanceController extends Controller
{
    /**
     * @OA\Get(
     *     path="/admin/attendances",
     *     tags={"Admin - Attendances"},
     *     summary="Daftar absensi",
     *     description="Menampilkan daftar semua data absensi karyawan",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Attendance")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function index()
    {
        return view('admin.attendances.index');
    }

    /**
     * @OA\Get(
     *     path="/admin/attendances/report",
     *     tags={"Admin - Attendances"},
     *     summary="Generate laporan absensi",
     *     description="Generate dan download laporan absensi dalam format PDF berdasarkan filter",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="month",
     *         in="query",
     *         description="Filter by month (format: Y-m, contoh: 2024-01)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="week",
     *         in="query",
     *         description="Filter by week (format: Y-W##, contoh: 2024-W01)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="division",
     *         in="query",
     *         description="Filter by division ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="jobTitle",
     *         in="query",
     *         description="Filter by job title ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success - Returns PDF file",
     *         @OA\MediaType(
     *             mediaType="application/pdf"
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function report(Request $request)
{
    $request->validate([
        'day' => 'nullable|date_format:Y-m-d',
        'week' => 'nullable',
        'month' => 'nullable|date_format:Y-m',
        'division' => 'nullable|exists:divisions,id',
        'jobTitle' => 'nullable|exists:job_titles,id',
    ]);

    $filterType = 'all';
    $carbon = Carbon::now();
    $start = null;
    $end = null;
    $dates = collect();

    /** =========================
     * Tentukan periode
     * ========================= */
    if ($request->day && preg_match('/^\d{4}-\d{2}-\d{2}$/', $request->day)) {
        $filterType = 'day';
        $start = Carbon::parse($request->day)->startOfDay();
        $end = Carbon::parse($request->day)->endOfDay();
    } elseif ($request->week && preg_match('/^\d{4}-W\d{2}$/', $request->week)) {
        $filterType = 'week';
        $start = Carbon::parse($request->week)->startOfWeek();
        $end = Carbon::parse($request->week)->endOfWeek();
    } elseif ($request->month && preg_match('/^\d{4}-\d{2}$/', $request->month)) {
        $filterType = 'month';
        $start = Carbon::parse($request->month)->startOfMonth();
        $end = Carbon::parse($request->month)->endOfMonth();
    }

    /** =========================
     * Generate daftar tanggal
     * ========================= */
    if ($start && $end) {
        $dates = collect($start->range($end))->map(fn ($d) => $d->copy());
    } else {
        $dates = Attendance::select('date')
            ->distinct()
            ->orderBy('date')
            ->pluck('date')
            ->map(fn ($d) => Carbon::parse($d));
    }

    /** =========================
     * Ambil data user + attendance
     * ========================= */
    $employees = User::where('group', 'user')
        ->when($request->division, fn ($q) => $q->where('division_id', $request->division))
        ->when($request->jobTitle, fn ($q) => $q->where('job_title_id', $request->jobTitle))
        ->get()
        ->map(function ($user) use ($filterType, $start, $end) {

            $attendanceQuery = Attendance::where('user_id', $user->id);

            if ($filterType !== 'all') {
                $attendanceQuery->whereBetween(
                    'date',
                    [$start->toDateString(), $end->toDateString()]
                );
            }

            $attendances = $attendanceQuery
                ->get(['id', 'status', 'date', 'time_in', 'time_out'])
                ->map(function ($v) {
                    return [
                        'status' => $v->status,
                        'date' => $v->date,
                        'time_in' => $v->time_in?->format('H:i:s'),
                        'time_out' => $v->time_out?->format('H:i:s'),
                    ];
                });

            $user->attendances = $attendances;
            return $user;
        });

    /** =========================
     * Generate PDF
     * ========================= */
    $pdf = Pdf::loadView('admin.attendances.report', [
        'employees' => $employees,
        'dates' => $dates,
        'filterType' => $filterType,
        'day' => $request->day,
        'week' => $request->week,
        'month' => $request->month,
        'division' => $request->division,
        'jobTitle' => $request->jobTitle,
        'start' => $start,
        'end' => $end,
    ])->setPaper($filterType === 'month' ? 'a3' : 'a4', 'landscape');

    return $pdf->stream();
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        //
    }
}
