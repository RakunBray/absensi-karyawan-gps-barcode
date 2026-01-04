<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barcode extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'value',
        'latitude',
        'longitude',
        'radius',
    ];

    protected $casts = [
        'latitude' => 'double',
        'longitude' => 'double',
        'radius' => 'float',
    ];

    public function getLatLngAttribute(): ?array
    {
        if (is_null($this->latitude) || is_null($this->longitude)) {
            return null;
        }
        
        return [
            'lat' => (float) $this->latitude,
            'lng' => (float) $this->longitude
        ];
    }

    public function getSafeFilenameAttribute(): string
    {
        $name = $this->name ?? $this->value;
        return preg_replace('/[^a-zA-Z0-9_-]/', '_', $name);
    }

    public function scopeWithinRadius($query, $lat, $lng, $radius = 100)
    {
        $haversine = "(6371000 * acos(cos(radians(?)) 
                     * cos(radians(latitude)) 
                     * cos(radians(longitude) - radians(?)) 
                     + sin(radians(?)) 
                     * sin(radians(latitude))))";
        
        return $query->select('*')
            ->selectRaw("{$haversine} AS distance", [$lat, $lng, $lat])
            ->having('distance', '<=', $radius)
            ->orderBy('distance');
    }

    public function setLatitudeAttribute($value)
    {
        $lat = (float) $value;
        if ($lat < -90 || $lat > 90) {
            throw new \InvalidArgumentException('Latitude must be between -90 and 90 degrees.');
        }
        $this->attributes['latitude'] = $lat;
    }

    public function setLongitudeAttribute($value)
    {
        $lng = (float) $value;
        if ($lng < -180 || $lng > 180) {
            throw new \InvalidArgumentException('Longitude must be between -180 and 180 degrees.');
        }
        $this->attributes['longitude'] = $lng;
    }

    public function setRadiusAttribute($value)
    {
        $radius = (float) $value;
        if ($radius <= 0) {
            throw new \InvalidArgumentException('Radius must be greater than 0.');
        }
        $this->attributes['radius'] = $radius;
    }

    public function isWithinRadius($lat, $lng): bool
    {
        if (is_null($this->latitude) || is_null($this->longitude)) {
            return false;
        }

        $earthRadius = 6371000;
        $dLat = deg2rad($lat - $this->latitude);
        $dLng = deg2rad($lng - $this->longitude);
        
        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($this->latitude)) * cos(deg2rad($lat)) *
             sin($dLng/2) * sin($dLng/2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distance = $earthRadius * $c;
        
        return $distance <= $this->radius;
    }
}