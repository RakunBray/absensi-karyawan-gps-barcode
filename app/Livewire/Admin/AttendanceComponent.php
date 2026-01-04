<?php

namespace App\Livewire\Admin;

use App\Livewire\Traits\AttendanceDetailTrait;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Laravel\Jetstream\InteractsWithBanner;
use Livewire\Component;
use Livewire\WithPagination;

class AttendanceComponent extends Component
{
    use AttendanceDetailTrait;
    use WithPagination, InteractsWithBanner;

    # filter
    public ?string $month = null;
    public ?string $week = null;
    public ?string $day = null;
    public ?string $division = null;
    public ?string $jobTitle = null;
    public ?string $search = null;

    public function updating($key): void
    {
        if (in_array($key, ['search', 'division', 'jobTitle', 'month', 'week' , 'day'])) {
            $this->resetPage();
        }
        if ($key === 'month') {
            $this->week = null;
        }
        if ($key === 'week') {
            $this->month = null;
        }
        if ($key === 'day') {
            $this->day = null;
        }
    }

    public function render()
{
    Carbon::setLocale('id');
    $monthOptions = [];
    $mDate = Carbon::now()->addMonth(); // Mulai dari bulan depan
    for ($i = 0; $i < 3; $i++) { // Loop 3 bulan ke belakang
        $monthOptions[] = [
            'value' => $mDate->format('Y-m'),        // Format untuk Backend: 2024-05
            'label' => $mDate->isoFormat('MMMM Y'),  // Format Tampilan: Mei 2024
        ];
        $mDate->subMonth();
    }
    $weekOptions = [];
    $wDate = Carbon::now()->addWeeks(2); // Mulai 2 minggu ke depan
    for ($i = 0; $i < 7; $i++) { // Loop 7 minggu ke belakang
        $start = $wDate->copy()->startOfWeek();
        $end   = $wDate->copy()->endOfWeek();
        
        $weekOptions[] = [
            'value' => $wDate->format('Y-\WW'), // Format Backend: 2024-W20
            'label' => 'Mg ' . $wDate->week . ' (' . $start->isoFormat('D MMM') . ' - ' . $end->isoFormat('D MMM') . ')',
        ];
        $wDate->subWeek();
    }
    $dates = [];
    try {
        if ($this->day) {
            $dates = [Carbon::parse($this->day)];
        } else if ($this->week && preg_match('/^\d{4}-W\d{2}$/', $this->week)) {
            $start = Carbon::parse($this->week)->startOfWeek();
            $end = Carbon::parse($this->week)->endOfWeek();
            $dates = $start->range($end)->toArray();
        } else if ($this->month && preg_match('/^\d{4}-\d{2}$/', $this->month)) {
            $start = Carbon::parse($this->month)->startOfMonth();
            $end = Carbon::parse($this->month)->endOfMonth();
            $dates = $start->range($end)->toArray();
        } else {
            // Get all unique dates from attendances
            $attendancesQuery = Attendance::query()
                ->when($this->division, function (Builder $q) {
                    return $q->whereHas('user', function (Builder $query) {
                        $query->where('division_id', $this->division);
                    });
                })
                ->when($this->jobTitle, function (Builder $q) {
                    return $q->whereHas('user', function (Builder $query) {
                        $query->where('job_title_id', $this->jobTitle);
                    });
                });

            $uniqueDates = $attendancesQuery->select('date')->distinct()->orderBy('date')->get()->map(function ($attendance) {
                return Carbon::parse($attendance->date);
            })->values()->toArray();
            
            $dates = $uniqueDates;
            
            // Ensure we don't try to use invalid strings in queries
            if ($this->week && !preg_match('/^\d{4}-W\d{2}$/', $this->week)) $this->week = null;
            if ($this->month && !preg_match('/^\d{4}-\d{2}$/', $this->month)) $this->month = null;
        }
    } catch (\Exception $e) {
        // Safe fallback - get all dates from attendances
        $uniqueDates = Attendance::select('date')->distinct()->orderBy('date')->get()->map(function ($attendance) {
            return Carbon::parse($attendance->date);
        })->values()->toArray();
        $dates = $uniqueDates;
    }

    // Get all employees for statistics (before pagination)
    $allEmployeesQuery = User::where('group', 'user')
        ->when($this->search, function (Builder $q) {
            return $q->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('nip', 'like', '%' . $this->search . '%');
        })
        ->when($this->division, fn (Builder $q) => $q->where('division_id', $this->division))
        ->when($this->jobTitle, fn (Builder $q) => $q->where('job_title_id', $this->jobTitle));

    $employeesCount = $allEmployeesQuery->count();

    // Calculate statistics based on selected filter
    $presentCount = 0;
    $lateCount = 0;
    $excusedCount = 0;
    $sickCount = 0;

    if ($this->week && preg_match('/^\d{4}-W\d{2}$/', $this->week)) {
        $attendances = Attendance::filter(
            week: $this->week,
            division: $this->division,
            jobTitle: $this->jobTitle,
        )->get();
        $presentCount = $attendances->where('status', 'present')->count();
        $lateCount = $attendances->where('status', 'late')->count();
        $excusedCount = $attendances->where('status', 'excused')->count();
        $sickCount = $attendances->where('status', 'sick')->count();
    } else if ($this->month && preg_match('/^\d{4}-\d{2}$/', $this->month)) {
        $attendances = Attendance::filter(
            month: $this->month,
            division: $this->division,
            jobTitle: $this->jobTitle,
        )->get();
        $presentCount = $attendances->where('status', 'present')->count();
        $lateCount = $attendances->where('status', 'late')->count();
        $excusedCount = $attendances->where('status', 'excused')->count();
        $sickCount = $attendances->where('status', 'sick')->count();
    } else {
        // All attendances
        $attendances = Attendance::query()
            ->when($this->division, function (Builder $q) {
                return $q->whereHas('user', function (Builder $query) {
                    $query->where('division_id', $this->division);
                });
            })
            ->when($this->jobTitle, function (Builder $q) {
                return $q->whereHas('user', function (Builder $query) {
                    $query->where('job_title_id', $this->jobTitle);
                });
            })
            ->get();
        $presentCount = $attendances->where('status', 'present')->count();
        $lateCount = $attendances->where('status', 'late')->count();
        $excusedCount = $attendances->where('status', 'excused')->count();
        $sickCount = $attendances->where('status', 'sick')->count();
    }

    $employees = $allEmployeesQuery
        ->paginate(20)->through(function (User $user) {
            $cacheKey = "attendance-{$user->id}-{$this->week}-{$this->month}-{$this->day}";
            $attendances = new Collection(Cache::remember(
                $cacheKey,
                now()->addDay(),
                function () use ($user) {
                    /** @var \Illuminate\Database\Eloquent\Builder $query */
                    $query = Attendance::filter(
                        userId: $user->id,
                        week: $this->week,
                        month: $this->month,
                    );

                    if ($this->day) {
                        $query = $query->whereDate('date', $this->day);
                    }

                    $attendances = $query->get(['id', 'status', 'date', 'latitude', 'longitude', 'attachment', 'note', 'time_in', 'time_out']);

                    return $attendances->map(function (Attendance $v) {
                        $v->setAttribute('coordinates', $v->lat_lng);
                        $v->setAttribute('lat', $v->latitude);
                        $v->setAttribute('lng', $v->longitude);
                        if ($v->attachment) {
                            $v->setAttribute('attachment', $v->attachment_url);
                        }
                        if ($v->shift) {
                            $v->setAttribute('shift', $v->shift->name);
                        }
                        if ($v->time_in) {
                            $v->setAttribute('time_in', $v->time_in->format('H:i:s'));
                        }
                        if ($v->time_out) {
                            $v->setAttribute('time_out', $v->time_out->format('H:i:s'));
                        }
                        return $v->getAttributes();
                    })->toArray();
                }
            ) ?? []);

            $user->attendances = $attendances;
            return $user;
        });

    return view('livewire.admin.attendance', [
        'employees' => $employees,
        'dates' => $dates,
        'employeesCount' => $employeesCount,
        'presentCount' => $presentCount,
        'lateCount' => $lateCount,
        'excusedCount' => $excusedCount,
        'sickCount' => $sickCount,
        'monthOptions' => $monthOptions, 
        'weekOptions' => $weekOptions,
    ]);
    }
}