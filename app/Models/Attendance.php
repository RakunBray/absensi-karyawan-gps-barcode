<?php

namespace App\Models;

use App\ExtendedCarbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class Attendance extends Model
{
    use HasFactory;
    use HasTimestamps;

    protected $fillable = [
        'user_id',
        'barcode_id',
        'date',
        'time_in',
        'time_out',
        'shift_id',
        'latitude',
        'longitude',
        'status',
        'note',
        'attachment',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'datetime:Y-m-d',
            'time_in' => 'datetime:H:i:s',
            'time_out' => 'datetime:H:i:s',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function barcode()
    {
        return $this->belongsTo(Barcode::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function getLatLngAttribute(): array|null
    {
        if (is_null($this->latitude) || is_null($this->longitude)) {
            return null;
        }
        return [
            'lat' => (float) $this->latitude,
            'lng' => (float) $this->longitude
        ];
    }
    
    public function scopeFilter(
        Builder $query,
        $date = null,
        $week = null,
        $month = null,
        $day = null,      
        $year = null,
        $userId = null,
        $division = null,
        $jobTitle = null,
        $education = null
    ): Builder {
        return $query
            ->when($day, function (Builder $q) use ($day) {
                try {
                    $q->where('date', Carbon::parse($day)->toDateString());
                } catch (\Exception $e) {
                    // Invalid date → abaikan
                }
            })
            ->when($week && !$day, function (Builder $q) use ($week) {
                try {
                    $start = Carbon::parse($week)->startOfWeek();
                    $end = Carbon::parse($week)->endOfWeek();
                    $q->whereBetween('date', [$start->toDateString(), $end->toDateString()]);
                } catch (\Exception $e) {
                    // Invalid week → abaikan
                }
            })
            ->when($month && !$week && !$date, function (Builder $q) use ($month) {
                try {
                    $parsed = Carbon::parse($month);
                    $q->whereMonth('date', $parsed->month)
                      ->whereYear('date', $parsed->year);
                } catch (\Exception $e) {
                    // Invalid month → abaikan
                }
            })
            ->when($year && !$month && !$week && !$date, function (Builder $q) use ($year) {
                try {
                    $q->whereYear('date', $year);
                } catch (\Exception $e) {
                    // Invalid year → abaikan
                }
            })
            ->when($userId, fn(Builder $q) => $q->where('user_id', $userId))
            ->when($division && !$userId, fn(Builder $q) => $q->whereHas('user', fn(Builder $qq) => $qq->where('division_id', $division)))
            ->when($jobTitle && !$userId, fn(Builder $q) => $q->whereHas('user', fn(Builder $qq) => $qq->where('job_title_id', $jobTitle)))
            ->when($education && !$userId, fn(Builder $q) => $q->whereHas('user', fn(Builder $qq) => $qq->where('education_id', $education)));
    }

    public function attachmentUrl(): ?Attribute
    {
        if (!$this->attachment) {
            return null;
        }

        return Attribute::get(function (): string {
            if (str_contains($this->attachment, 'https://') || str_contains($this->attachment, 'http://')) {
                return $this->attachment;
            }
            return Storage::disk(config('jetstream.attachment_disk', 'public'))->url($this->attachment);
        });
    }

    public static function clearUserAttendanceCache(Authenticatable $user, Carbon $date)
    {
        if (is_null($user)) return false;
        $date = new ExtendedCarbon($date);
        $monthYear = "$date->month-$date->year";
        $week = $date->yearWeekString();
        $ymd = $date->format('Y-m-d');

        try {
            Cache::forget("attendance-$user->id-$monthYear");
            Cache::forget("attendance-$user->id-$week");
            Cache::forget("attendance-$user->id-$ymd");
            return true;
        } catch (\Throwable $_) {
            return false;
        }
    }
}