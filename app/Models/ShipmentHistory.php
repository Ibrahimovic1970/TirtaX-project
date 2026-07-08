<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ShipmentHistory extends Model
{
    protected $fillable = ['shipment_id', 'status', 'description', 'location', 'photo_url'];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    // Menampilkan daftar semua pengiriman (untuk simulasi dashboard operasional)
    public function index()
    {
        // Ambil semua pengiriman, urutkan dari yang terbaru
        $shipments = Shipment::with('user')->orderBy('created_at', 'desc')->get();
        return view('shipments.index', compact('shipments'));
    }

    // Mengupdate status pengiriman
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:created,paid,picked_up,in_transit,delivered,cancelled',
            'description' => 'required|string',
            'location' => 'nullable|string',
        ]);

        $shipment = Shipment::findOrFail($id);

        // Update status utama di tabel shipments
        $shipment->update(['status' => $request->status]);

        // Catat ke history (Timeline)
        ShipmentHistory::create([
            'shipment_id' => $shipment->id,
            'status' => $request->status,
            'description' => $request->description,
            'location' => $request->location ?: 'Sistem TirtaX',
        ]);

        return redirect()->route('shipments.index')->with('success', 'Status pengiriman berhasil diupdate!');
    }
}