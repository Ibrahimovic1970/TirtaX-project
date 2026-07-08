<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    protected $fillable = [
        'origin_city',
        'destination_city',
        'price_per_kg',
        'base_price',
        'service_type',
        'estimation_days',
        'is_active',
    ];

    protected $casts = [
        'price_per_kg' => 'decimal:2',
        'base_price' => 'decimal:2',
        'is_active' => 'boolean',
        'estimation_days' => 'integer',
    ];

    /**
     * Hitung ongkir berdasarkan berat
     */
    public function calculateCost($weight)
    {
        return $this->base_price + ($this->price_per_kg * $weight);
    }

    /**
     * Scope untuk tarif aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Cari tarif berdasarkan rute
     */
    public static function findByRoute($origin, $destination, $serviceType = 'REGULER')
    {
        return static::active()
            ->where('origin_city', 'LIKE', '%' . $origin . '%')
            ->where('destination_city', 'LIKE', '%' . $destination . '%')
            ->where('service_type', $serviceType)
            ->first();
    }
}