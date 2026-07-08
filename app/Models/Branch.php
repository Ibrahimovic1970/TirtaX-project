<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'address',
        'city',
        'province',
        'postal_code',
        'phone',
        'email',
        'pic_name',
        'pic_phone',
        'latitude',
        'longitude',
        'is_active',
        'is_main',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_main' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Scope untuk cabang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk cabang utama
     */
    public function scopeMain($query)
    {
        return $query->where('is_main', true);
    }

    /**
     * Generate kode cabang otomatis
     */
    public static function generateCode($city)
    {
        $code = strtoupper(substr($city, 0, 3)) . rand(100, 999);

        // Cek apakah kode sudah ada
        while (static::where('code', $code)->exists()) {
            $code = strtoupper(substr($city, 0, 3)) . rand(100, 999);
        }

        return $code;
    }

    /**
     * Get full address
     */
    public function getFullAddressAttribute()
    {
        return "{$this->address}, {$this->city}, {$this->province} {$this->postal_code}";
    }
}