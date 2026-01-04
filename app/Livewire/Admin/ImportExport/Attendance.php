<?php

namespace App\Livewire\Admin\ImportExport;

use App\Models\Attendance as AttendanceModel;
use App\Models\Division;
use App\Models\JobTitle; // Pastikan model ini ada
use Livewire\Component;
use Livewire\WithPagination; 
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendancesExport;

class Attendance extends Component
{
    use WithPagination; 

    // Filter Variables
    public $periodType = 'range'; // 'range' atau 'monthly'
    public $startDate;
    public $endDate;
    public $selectedMonth;
    
    // Filter Tambahan
    public $division;
    public $job_title; 

    public function mount()
    {
        // Default: Hari ini
        $this->startDate     = Carbon::today()->format('Y-m-d');
        $this->endDate       = Carbon::today()->format('Y-m-d');
        $this->selectedMonth = Carbon::today()->format('Y-m');
    }

    public function updated($propertyName)
    {
        $this->resetPage(); 
    }

    public function export()
    {
        return Excel::download(
            new AttendancesExport(
                type:          $this->periodType,
                startDate:     $this->startDate,
                endDate:       $this->endDate,
                selectedMonth: $this->selectedMonth,
                division:      $this->division,
                jobTitle:      $this->job_title
            ),
            'Laporan-Absensi-' . now()->format('Y-m-d_H-i') . '.xlsx'
        );
    }

    public function render()
    {
        $query = AttendanceModel::query()
            ->with(['user.division', 'user.jobTitle', 'shift']);

        // --- FILTER USER ---
        $query->whereHas('user', function ($q) {
            $q->where('group', 'user'); 

            if ($this->division) {
                $q->where('division_id', $this->division);
            }
            if ($this->job_title) {
                $q->where('job_title_id', $this->job_title); 
            }
        });

        // --- FILTER WAKTU ---
        if ($this->periodType === 'range') {
            if ($this->startDate && $this->endDate) {
                $query->whereBetween('date', [$this->startDate, $this->endDate]);
            }
        } elseif ($this->periodType === 'monthly') {
            if ($this->selectedMonth) {
                $date = Carbon::parse($this->selectedMonth);
                $query->whereYear('date', $date->year)
                      ->whereMonth('date', $date->month);
            }
        }

        $attendances = $query->orderBy('date', 'desc')->paginate(10);

        return view('livewire.admin.import-export.attendance', [
            'attendances' => $attendances
        ]);
    }
}
