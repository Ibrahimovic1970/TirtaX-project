<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentTracking extends Model
{
    use HasFactory;

    protected $table = 'shipment_trackings';

    protected $fillable = [
        'shipment_id',
        'status',
        'description',
        'location',
        'tracked_at',
    ];

    protected $casts = [
        'tracked_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the shipment that owns this tracking
     */
    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    /**
     * Get status label in Indonesian
     */
    public function getStatusLabel()
    {
        $labels = [
            'picked_up'         => 'Diambil Kurir',
            'in_transit'        => 'Dalam Perjalanan',
            'arrived_at_branch' => 'Sampai di Cabang',
            'out_for_delivery'  => 'Sedang Diantar',
            'delivered'         => 'Terkirim',
            'paid'              => 'Pembayaran Berhasil',
        ];

        return $labels[$this->status] ?? ucfirst(str_replace('_', ' ', $this->status));
    }

    /**
     * Get status color
     */
    public function getStatusColor()
    {
        $colors = [
            'picked_up'         => 'bg-blue-100 text-blue-800',
            'in_transit'        => 'bg-indigo-100 text-indigo-800',
            'arrived_at_branch' => 'bg-purple-100 text-purple-800',
            'out_for_delivery'  => 'bg-orange-100 text-orange-800',
            'delivered'         => 'bg-green-100 text-green-800',
            'paid'              => 'bg-green-100 text-green-800',
        ];

        return $colors[$this->status] ?? 'bg-gray-100 text-gray-800';
    }
}
