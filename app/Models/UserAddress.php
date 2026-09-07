<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'recipient_name',
        'phone',
        'province_id',
        'district_id',
        'ward_id',
        'city',
        'district',
        'ward',
        'address',
        'label',
        'is_default',
        'notes',
    ];

    protected $casts = [
        'is_default'  => 'boolean',
        'province_id' => 'integer',
        'district_id' => 'integer',
        'ward_id'     => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function districtRelation()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function wardRelation()
    {
        return $this->belongsTo(Ward::class, 'ward_id');
    }

    public function getFullAddressAttribute(): string
    {
        $wardName = $this->wardRelation?->name ?? $this->ward;
        $districtName = $this->districtRelation?->name ?? $this->district;
        $cityName = $this->province?->name ?? $this->city;

        $parts = array_filter([$this->address, $wardName, $districtName, $cityName]);
        return implode(', ', $parts);
    }
}
