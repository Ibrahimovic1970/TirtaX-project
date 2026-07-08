<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tracking_number',
        'origin_city',
        'destination_city',
        'receiver_name',
        'receiver_phone',
        'receiver_address',
        'weight',
        'total_cost',
        'status',
        'rating',
        'feedback',
    ];

    protected $casts = [
        'rating' => 'integer',
        'weight' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    /**
     * Boot method untuk auto-generate tracking number
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($shipment) {
            if (empty($shipment->tracking_number)) {
                $shipment->tracking_number = 'TRX-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            }
        });
    }

    /**
     * Relasi ke User (pengirim)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke ShipmentHistory
     */
    public function histories()
    {
        return $this->hasMany(ShipmentHistory::class);
    }

    /**
     * Cek apakah sudah dirating
     */
    public function hasRated()
    {
        return !is_null($this->rating);
    }

    /**
     * Cek apakah bisa dirating
     */
    public function canBeRated()
    {
        return $this->status === 'delivered' && is_null($this->rating);
    }

    /**
     * Format rating untuk tampilan
     */
    public function getRatingDisplayAttribute()
    {
        if (is_null($this->rating)) {
            return 'Belum dinilai';
        }
        return $this->rating . ' / 5';
    }
}