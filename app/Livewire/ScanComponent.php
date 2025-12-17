<?php

namespace App\Livewire;

use App\ExtendedCarbon;
use App\Models\Attendance;
use App\Models\Barcode;
use App\Models\Shift;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Ballen\Distical\Calculator as DistanceCalculator;
use Ballen\Distical\Entities\LatLong;
use Illuminate\Support\Carbon;

class ScanComponent extends Component
{
    public ?Attendance $attendance = null;
    public $shift_id = null;
    public $shifts = null;
    public ?array $currentLiveCoords = null;
    public string $successMsg = '';
    public bool $isAbsence = false;

    public function scan(string $barcodeValue)
    {
        // VALIDASI AWAL
        if (is_null($this->currentLiveCoords)) {
            return __('Invalid location');
        }

        if (is_null($this->shift_id)) {
            return __('Invalid shift');
        }

        if (!Auth::check()) {
            return __('Unauthorized');
        }

        /** @var Barcode|null */
        $barcode = Barcode::firstWhere('value', $barcodeValue);
        if (!$barcode) {
            return __('Invalid barcode');
        }

        // LOKASI BARCODE & USER
        $barcodeLocation = new LatLong(
            $barcode->latLng['lat'],
            $barcode->latLng['lng']
        );

        $userLocation = new LatLong(
            $this->currentLiveCoords[0],
            $this->currentLiveCoords[1]
        );

        $existingAttendance = Attendance::where('user_id', Auth::user()->id)
            ->where('date', date('Y-m-d'))
            ->where('barcode_id', $barcode->id)
            ->first();
        if (!$existingAttendance) {
            $distance = $this->calculateDistance($userLocation, $barcodeLocation);

            if ($distance > $barcode->radius) {
                return __('Location out of range') .
                    ": {$distance}m. Max: {$barcode->radius}m";
            }
        }
        if (!$existingAttendance) {
            // ABSENSI MASUK
            $attendance = $this->createAttendance($barcode);
            $this->successMsg = __('Attendance In Successful');
        } else {
            // ABSENSI KELUAR
            $attendance = $existingAttendance;
            $attendance->update([
                'time_out' => date('H:i:s'),
            ]);
            $this->successMsg = __('Attendance Out Successful');
        }

        if ($attendance) {
            $this->setAttendance($attendance->fresh());
            Attendance::clearUserAttendanceCache(
                Auth::user(),
                Carbon::parse($attendance->date)
            );
            return true;
        }

        return __('Attendance failed');
    }

    public function calculateDistance(LatLong $a, LatLong $b): int
    {
        $distanceCalculator = new DistanceCalculator($a, $b);
        return (int) floor(
            $distanceCalculator->get()->asKilometres() * 1000
        );
    }

    public function createAttendance(Barcode $barcode): Attendance
    {
        $now = Carbon::now();
        $date = $now->format('Y-m-d');
        $timeIn = $now->format('H:i:s');

        /** @var Shift */
        $shift = Shift::find($this->shift_id);

        $status = Carbon::now()
            ->setTimeFromTimeString($shift->start_time)
            ->lt($now)
            ? 'late'
            : 'present';

        return Attendance::create([
            'user_id' => Auth::user()->id,
            'barcode_id' => $barcode->id,
            'date' => $date,
            'time_in' => $timeIn,
            'time_out' => null,
            'shift_id' => $shift->id,
            'latitude' => (double) $this->currentLiveCoords[0],
            'longitude' => (double) $this->currentLiveCoords[1],
            'status' => $status,
            'note' => null,
            'attachment' => null,
        ]);
    }

    protected function setAttendance(Attendance $attendance): void
    {
        $this->attendance = $attendance;
        $this->shift_id = $attendance->shift_id;
        $this->isAbsence = !in_array($attendance->status, ['present', 'late']);
    }

    public function getAttendance(): ?array
    {
        if (!$this->attendance) {
            return null;
        }

        return [
            'time_in' => $this->attendance->time_in,
            'time_out' => $this->attendance->time_out,
        ];
    }

    public function mount(): void
    {
        $this->shifts = Shift::all();

        $attendance = Attendance::where('user_id', Auth::user()->id)
            ->where('date', date('Y-m-d'))
            ->first();

        if ($attendance) {
            $this->setAttendance($attendance);
        } else {
            $closest = ExtendedCarbon::now()
                ->closestFromDateArray(
                    $this->shifts->pluck('start_time')->toArray()
                );

            $this->shift_id = $this->shifts
                ->first(fn (Shift $shift) =>
                    $shift->start_time === $closest->format('H:i:s')
                )->id;
        }
    }

    public function render()
    {
        return view('livewire.scan');
    }
}
