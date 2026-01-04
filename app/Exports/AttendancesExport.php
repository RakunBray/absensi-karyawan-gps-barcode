<?php

namespace App\Exports;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Carbon\CarbonPeriod;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents; // Wajib untuk styling
use Maatwebsite\Excel\Events\AfterSheet;   // Event setelah sheet dibuat
use PhpOffice\PhpSpreadsheet\Cell\Coordinate; // Helper huruf kolom (A, B, AA...)
use PhpOffice\PhpSpreadsheet\Style\Alignment; // Helper perataan text

class AttendancesExport implements FromView, WithEvents
{
    public function __construct(
        private $type,          // 'range' atau 'monthly'
        private $startDate,     // dipakai jika type = range
        private $endDate,       // dipakai jika type = range
        private $selectedMonth, // dipakai jika type = monthly
        private $division = null,
        private $jobTitle = null
    ) {}

    public function view(): View
    {
        Carbon::setLocale('id');

        $start = null;
        $end   = null;
        $dates = [];

        // ---------------------------------------------------------
        // 1. TENTUKAN PERIODE TANGGAL
        // ---------------------------------------------------------
        
        if ($this->type === 'range') {
            // Mode Custom Range
            $start = $this->startDate ? Carbon::parse($this->startDate)->startOfDay() : now()->startOfDay();
            $end   = $this->endDate   ? Carbon::parse($this->endDate)->endOfDay()     : now()->endOfDay();
        } else {
            // Mode Bulanan
            if ($this->selectedMonth) {
                $date  = Carbon::parse($this->selectedMonth); 
                $start = $date->copy()->startOfMonth();
                $end   = $date->copy()->endOfMonth();
            } else {
                $start = now()->startOfMonth();
                $end   = now()->endOfMonth();
            }
        }

        // Generate Array Tanggal (Untuk Header)
        $period = CarbonPeriod::create($start, $end);
        foreach ($period as $date) {
            $dates[] = $date;
        }

        // ---------------------------------------------------------
        // 2. QUERY DATA KARYAWAN & ABSENSI
        // ---------------------------------------------------------

        $employees = User::where('group', 'user')
            // Filter Tambahan
            ->when($this->division, fn (Builder $q) => $q->where('division_id', $this->division))
            ->when($this->jobTitle, fn (Builder $q) => $q->where('position_id', $this->jobTitle)) // Sesuaikan nama kolom jabatan di DB
            
            // Eager Load Absensi (OPTIMASI PERFORMA)
            // Kita load absensi hanya pada range tanggal yang dipilih agar ringan
            ->with(['attendances' => function($q) use ($start, $end) {
                $q->whereBetween('date', [$start->format('Y-m-d'), $end->format('Y-m-d')]);
            }, 'division', 'jobtitle'])
            ->get();

        return view('admin.import-export.export-attendances', [
            'employees' => $employees,
            'dates'     => $dates 
        ]);
    }

    /**
     * ---------------------------------------------------------
     * 3. STYLING EXCEL (AGAR TIDAK RAPAT)
     * ---------------------------------------------------------
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Hitung ulang range tanggal untuk loop kolom
                $start = $this->type === 'range' 
                    ? ($this->startDate ? Carbon::parse($this->startDate) : now())
                    : ($this->selectedMonth ? Carbon::parse($this->selectedMonth)->startOfMonth() : now()->startOfMonth());
                
                $end = $this->type === 'range' 
                    ? ($this->endDate ? Carbon::parse($this->endDate) : now()) 
                    : ($this->selectedMonth ? Carbon::parse($this->selectedMonth)->endOfMonth() : now()->endOfMonth());
                
                $daysCount = $start->diffInDays($end) + 1;

                // A. SET LEBAR KOLOM IDENTITAS (Manual)
                $sheet->getColumnDimension('A')->setWidth(5);  // No
                $sheet->getColumnDimension('B')->setWidth(35); // Nama (Lebih Lebar)
                $sheet->getColumnDimension('C')->setWidth(18); // NIP
                $sheet->getColumnDimension('D')->setWidth(20); // Divisi
                $sheet->getColumnDimension('E')->setWidth(20); // Jabatan

                // B. SET LEBAR KOLOM TANGGAL (Otomatis Loop)
                // Kolom F adalah index ke-6.
                $startColumnIndex = 6; 
                for ($i = 0; $i < $daysCount; $i++) {
                    $colString = Coordinate::stringFromColumnIndex($startColumnIndex + $i);
                    $sheet->getColumnDimension($colString)->setWidth(5); // Kotak Kecil
                }

                // C. SET LEBAR KOLOM TOTAL (H, T, I, S, A)
                $recapStartIndex = $startColumnIndex + $daysCount;
                for ($j = 0; $j < 5; $j++) {
                    $colString = Coordinate::stringFromColumnIndex($recapStartIndex + $j);
                    $sheet->getColumnDimension($colString)->setWidth(6);
                }

                // D. RATA TENGAH (CENTER ALIGNMENT)
                // Dari kolom tanggal pertama (F) sampai kolom Total terakhir
                $firstDateCol = 'F';
                $lastColString = Coordinate::stringFromColumnIndex($recapStartIndex + 4);
                
                // Center header & data dari baris 3 sampai bawah
                $sheet->getStyle("{$firstDateCol}3:{$lastColString}1000")
                      ->getAlignment()
                      ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                      ->setVertical(Alignment::VERTICAL_CENTER);

                // E. HEADER JUDUL (Baris 3 & 4)
                $sheet->getRowDimension(3)->setRowHeight(25);
                $sheet->getRowDimension(4)->setRowHeight(20);
            },
        ];
    }
}