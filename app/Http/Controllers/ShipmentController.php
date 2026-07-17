<?php
namespace App\Http\Controllers;

use App\Mail\ShipmentStatusUpdateMail;
use App\Models\Shipment;
use App\Models\ShipmentHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ShipmentController extends Controller
{
    /**
     * Tampilkan form buat pengiriman baru (Customer)
     */
    public function create()
    {
        // Ambil daftar cabang aktif untuk dropdown kota
        $branches = \App\Models\Branch::active()
            ->select('city', 'province')
            ->distinct()
            ->orderBy('city')
            ->get();

        // Ambil daftar layanan aktif
        $services = \App\Models\Rate::active()
            ->select('service_type')
            ->distinct()
            ->orderBy('service_type')
            ->get()
            ->pluck('service_type');

        return view('shipments.create', compact('branches', 'services'));
    }

    /**
     * Simpan data pengiriman ke database (Customer) - DENGAN MASTER TARIF
     */
    public function store(Request $request)
    {
        $request->validate([
            'origin_city'      => 'required|string|max:100',
            'destination_city' => 'required|string|max:100',
            'receiver_name'    => 'required|string|max:100',
            'receiver_phone'   => 'required|string|max:20',
            'receiver_address' => 'required|string',
            'weight'           => 'required|numeric|min:0.1',
            'service_type'     => 'required|in:REGULER,EKONOMI,EXPRESS,SAMEDAY',
            'total_cost'       => 'required|numeric|min:1000',
        ]);

        // Validasi tarif dari database (security check)
        $rate = \App\Models\Rate::findByRoute(
            $request->origin_city,
            $request->destination_city,
            $request->service_type
        );

        if (! $rate) {
            return back()->with('error', 'Maaf, tarif untuk rute ini belum tersedia. Silakan hubungi admin.')
                ->withInput();
        }

        // Hitung ulang ongkir di server (jangan percaya input client)
        $calculatedCost = $rate->calculateCost($request->weight);

        // Validasi total_cost tidak berbeda jauh dari kalkulasi server
        if (abs($calculatedCost - $request->total_cost) > 1000) {
            return back()->with('error', 'Terjadi kesalahan perhitungan ongkir. Silakan coba lagi.')
                ->withInput();
        }

        $shipment = Shipment::create([
            'user_id'          => Auth::id(),
            'origin_city'      => $request->origin_city,
            'destination_city' => $request->destination_city,
            'receiver_name'    => $request->receiver_name,
            'receiver_phone'   => $request->receiver_phone,
            'receiver_address' => $request->receiver_address,
            'weight'           => $request->weight,
            'total_cost'       => $calculatedCost, // Gunakan kalkulasi server
            'status'           => 'created',
        ]);

        ShipmentHistory::create([
            'shipment_id' => $shipment->id,
            'status'      => 'created',
            'description' => 'Pesanan berhasil dibuat. Menunggu pembayaran.',
            'location'    => 'Sistem TirtaX',
        ]);

        return redirect()->route('tracking.show', $shipment->tracking_number)
            ->with('success', 'Pengiriman berhasil dibuat! Nomor Resi Anda: ' . $shipment->tracking_number);
    }

    /**
     * Halaman publik untuk melacak resi
     */
    public function showTracking($trackingNumber)
    {
        $shipment = Shipment::with('histories')->where('tracking_number', $trackingNumber)->first();

        if (! $shipment) {
            return view('shipments.track', ['error' => 'Nomor resi tidak ditemukan.']);
        }

        return view('shipments.track', compact('shipment'));
    }

    /**
     * Search tracking dari homepage
     */
    public function searchTracking(Request $request)
    {
        $request->validate([
            'tracking_number' => 'required|string',
        ]);

        return redirect()->route('tracking.show', $request->tracking_number);
    }

    /**
     * Dashboard Operasional (Admin) - Lihat semua pengiriman
     */
    public function index()
    {
        $shipments = Shipment::with('user')->orderBy('created_at', 'desc')->get();
        return view('shipments.index', compact('shipments'));
    }

    /**
     * Update status pengiriman (Admin)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status'      => 'required|in:created,paid,picked_up,in_transit,delivered,cancelled',
            'description' => 'required|string',
            'location'    => 'nullable|string',
        ]);

        $shipment = Shipment::findOrFail($id);
        $shipment->update(['status' => $request->status]);

        ShipmentHistory::create([
            'shipment_id' => $shipment->id,
            'status'      => $request->status,
            'description' => $request->description,
            'location'    => $request->location ?: 'Sistem TirtaX',
        ]);

        return redirect()->route('shipments.index')->with('success', 'Status pengiriman berhasil diupdate!');
    }

    /**
     * Dashboard Customer - Riwayat pengiriman
     */
    public function myShipments()
    {
        $shipments = Shipment::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('shipments.my-shipments', compact('shipments'));
    }

    /**
     * Halaman Detail Pengiriman untuk Customer
     */
    public function shipmentDetail($id)
    {
        $shipment = Shipment::with(['histories', 'user'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('shipments.detail', compact('shipment'));
    }

    /**
     * Download Invoice Pengiriman
     */
    public function downloadInvoice($id)
    {
        $shipment = Shipment::with(['histories', 'user'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Hanya bisa download invoice jika sudah dibayar
        if (! in_array($shipment->status, ['paid', 'picked_up', 'in_transit', 'delivered'])) {
            return back()->with('error', 'Invoice hanya tersedia setelah pembayaran dilakukan.');
        }

        return view('shipments.invoice', compact('shipment'));
    }

    /**
     * Submit Rating & Feedback
     */
    public function submitReview(Request $request, $id)
    {
        $shipment = Shipment::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Hanya bisa rating jika status delivered
        if ($shipment->status !== 'delivered') {
            return back()->with('error', 'Rating hanya dapat diberikan setelah paket diterima.');
        }

        // Cek apakah sudah pernah rating
        if ($shipment->rating !== null) {
            return back()->with('error', 'Anda sudah memberikan rating untuk pengiriman ini.');
        }

        $request->validate([
            'rating'   => 'required|integer|between:1,5',
            'feedback' => 'nullable|string|max:500',
        ]);

        $shipment->update([
            'rating'   => $request->rating,
            'feedback' => $request->feedback,
        ]);

        return back()->with('success', 'Terima kasih! Rating & feedback Anda telah disimpan.');
    }

    /**
     * Cancel Pengiriman oleh Customer
     */
    public function cancelShipment(Request $request, $id)
    {
        $shipment = Shipment::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Hanya bisa cancel jika status masih 'created' (belum dibayar)
        if ($shipment->status !== 'created') {
            return back()->with('error', 'Pengiriman hanya dapat dibatalkan sebelum pembayaran dilakukan.');
        }

        $request->validate([
            'cancel_reason' => 'required|string|min:10|max:500',
        ]);

        // Update status ke cancelled
        $shipment->update(['status' => 'cancelled']);

        // Catat history
        ShipmentHistory::create([
            'shipment_id' => $shipment->id,
            'status'      => 'cancelled',
            'description' => 'Pengiriman dibatalkan oleh customer. Alasan: ' . $request->cancel_reason,
            'location'    => 'Customer Panel',
        ]);

        return redirect()->route('shipments.detail', $shipment->id)
            ->with('success', 'Pengiriman berhasil dibatalkan.');
    }

    /**
     * Customer Dashboard
     */
    public function customerDashboard()
    {
        $recentShipments = Shipment::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $stats = [
            'total'     => Shipment::where('user_id', Auth::id())->count(),
            'pending'   => Shipment::where('user_id', Auth::id())->where('status', 'created')->count(),
            'process'   => Shipment::where('user_id', Auth::id())->whereIn('status', ['paid', 'picked_up', 'in_transit'])->count(),
            'delivered' => Shipment::where('user_id', Auth::id())->where('status', 'delivered')->count(),
        ];

        return view('customer.dashboard', compact('recentShipments', 'stats'));
    }

    /**
     * Tampilkan halaman pembayaran dengan Midtrans
     */
    public function showPayment($id)
    {
        $shipment = Shipment::findOrFail($id);

        // Check authorization
        if (Auth::user()->role === 'customer' && $shipment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        // Check if payment is needed
        if ($shipment->status !== 'created' && $shipment->status !== 'pending') {
            return redirect()->route('shipments.detail', $shipment->id)
                ->with('error', 'Pengiriman ini sudah dibayar atau tidak memerlukan pembayaran.');
        }

        // Check if Midtrans keys are configured
        if (! config('midtrans.server_key') || ! config('midtrans.client_key')) {
            return redirect()->route('shipments.detail', $shipment->id)
                ->with('error', 'Payment gateway belum dikonfigurasi. Silakan hubungi administrator.');
        }

        // Setup Midtrans configuration
        \Midtrans\Config::$serverKey    = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized  = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds        = config('midtrans.is_3ds');

        // Generate UNIQUE order_id untuk setiap transaksi
        $uniqueOrderId = $shipment->tracking_number . '-' . time() . '-' . substr(md5(uniqid()), 0, 6);

        // Prepare Midtrans transaction parameters
        $params = [
            'transaction_details' => [
                'order_id'     => $uniqueOrderId,
                'gross_amount' => (int) $shipment->total_cost,
            ],
            'customer_details'    => [
                'first_name' => $shipment->receiver_name ?? Auth::user()->name,
                'email'      => Auth::user()->email,
                'phone'      => $shipment->receiver_phone ?? '',
            ],
            'item_details'        => [
                [
                    'id'       => 'SHIPPING-' . $shipment->id,
                    'price'    => (int) $shipment->total_cost,
                    'quantity' => 1,
                    'name'     => 'Jasa Pengiriman ' . $shipment->origin_city . ' - ' . $shipment->destination_city,
                ],
            ],
        ];

        try {
            // Get Snap Payment Page URL
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // Store the unique order_id in session
            session(['midtrans_order_id' => $uniqueOrderId]);

            return view('shipments.payment', compact('shipment', 'snapToken', 'uniqueOrderId'));
        } catch (\Exception $e) {
            \Log::error('Midtrans Error: ' . $e->getMessage());

            $errorMessage = $e->getMessage();

            if (strpos($errorMessage, 'order_id has already been taken') !== false) {
                $errorMessage = 'Order ID sudah digunakan. Silakan refresh halaman dan coba lagi.';
            } elseif (strpos($errorMessage, '401') !== false || strpos($errorMessage, 'unauthorized') !== false) {
                $errorMessage = 'Server Key atau Client Key tidak valid. Silakan periksa konfigurasi Midtrans Anda.';
            } elseif (strpos($errorMessage, '400') !== false) {
                $errorMessage = 'Parameter transaksi tidak valid. Silakan coba lagi.';
            }

            return redirect()->route('shipments.detail', $shipment->id)
                ->with('error', 'Gagal menghubungi payment gateway: ' . $errorMessage);
        }
    }

/**
 * Payment success callback
 */
    public function paymentSuccess($id)
    {
        $shipment = Shipment::findOrFail($id);

        // Update status to paid (manual update untuk memastikan status berubah)
        if ($shipment->status !== 'paid') {
            $shipment->update([
                'status' => 'paid',
            ]);

            // Add tracking history
            try {
                \App\Models\ShipmentTracking::create([
                    'shipment_id' => $shipment->id,
                    'status'      => 'paid',
                    'description' => 'Pembayaran berhasil dikonfirmasi melalui Midtrans',
                    'location'    => 'Sistem Pembayaran',
                    'tracked_at'  => now(),
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to create tracking history: ' . $e->getMessage());
                // Jangan throw error, biarkan proses tetap berjalan
            }
        }

        return view('shipments.payment-success', compact('shipment'));
    }

    /**
     * Dashboard Kurir - Daftar tugas pengiriman
     */
    public function courierDashboard()
    {
        if (Auth::user()->role !== 'courier') {
            abort(403, 'Akses ditolak. Halaman ini khusus untuk kurir.');
        }

        // Ambil paket yang butuh ditangani kurir
        $tasks = Shipment::whereIn('status', ['paid', 'picked_up', 'in_transit'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Hitung statistik
        $stats = [
            'total'      => $tasks->count(),
            'paid'       => $tasks->where('status', 'paid')->count(),
            'picked_up'  => $tasks->where('status', 'picked_up')->count(),
            'in_transit' => $tasks->where('status', 'in_transit')->count(),
        ];

        return view('courier.dashboard', compact('tasks', 'stats'));
    }

    /**
     * Detail pengiriman untuk kurir
     */
    public function courierShipmentDetail($id)
    {
        if (Auth::user()->role !== 'courier') {
            abort(403, 'Akses ditolak.');
        }

        $shipment = Shipment::where('id', $id)
            ->whereIn('status', ['paid', 'picked_up', 'in_transit'])
            ->firstOrFail();

        return view('courier.shipment-detail', compact('shipment'));
    }

    /**
     * Update status pengiriman oleh kurir
     */
    public function courierUpdateStatus(Request $request, $id)
    {
        if (Auth::user()->role !== 'courier') {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'status'      => 'required|in:picked_up,in_transit,delivered',
            'description' => 'required|string',
            'location'    => 'nullable|string',
            'photo'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $shipment = Shipment::where('id', $id)
            ->whereIn('status', ['paid', 'picked_up', 'in_transit'])
            ->firstOrFail();

        // Validasi transisi status
        $allowedTransitions = [
            'paid'       => ['picked_up'],
            'picked_up'  => ['in_transit'],
            'in_transit' => ['delivered'],
        ];

        if (! in_array($request->status, $allowedTransitions[$shipment->status] ?? [])) {
            return back()->with('error', 'Transisi status tidak valid.');
        }

        // Handle upload foto
        $photoUrl = null;
        if ($request->hasFile('photo')) {
            $photo    = $request->file('photo');
            $filename = 'pod_' . time() . '_' . $shipment->tracking_number . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('uploads/pod'), $filename);
            $photoUrl = '/uploads/pod/' . $filename;
        }

        // WAJIB upload foto saat status delivered
        if ($request->status === 'delivered' && ! $photoUrl) {
            return back()->with('error', 'Upload foto bukti pengiriman WAJIB saat status Delivered.');
        }

        // Update status
        $shipment->update(['status' => $request->status]);

        // Catat history
        $history = ShipmentHistory::create([
            'shipment_id' => $shipment->id,
            'status'      => $request->status,
            'description' => $request->description,
            'location'    => $request->location ?: 'Lokasi Kurir',
            'photo_url'   => $photoUrl,
        ]);

        // KIRIM EMAIL NOTIFIKASI KE CUSTOMER
        try {
            Mail::to($shipment->user->email)->send(new ShipmentStatusUpdateMail($shipment, $history));
        } catch (\Exception $e) {
            Log::error('Gagal kirim email notifikasi status: ' . $e->getMessage());
        }

        return redirect()->route('courier.dashboard')
            ->with('success', 'Status pengiriman berhasil diupdate! Email notifikasi telah dikirim ke customer.');
    }

    /**
     * Dashboard Admin - Statistik & Overview
     */
    public function adminDashboard()
    {
        $stats = [
            'total'         => Shipment::count(),
            'revenue'       => Shipment::where('status', '!=', 'cancelled')->sum('total_cost'),
            'process'       => Shipment::whereIn('status', ['created', 'paid', 'picked_up', 'in_transit'])->count(),
            'delivered'     => Shipment::where('status', 'delivered')->count(),
            'pending'       => Shipment::where('status', 'created')->count(),
            'paid'          => Shipment::where('status', 'paid')->count(),
            'picked_up'     => Shipment::where('status', 'picked_up')->count(),
            'cancelled'     => Shipment::where('status', 'cancelled')->count(),
            'avg_rating'    => number_format(Shipment::whereNotNull('rating')->avg('rating'), 1),
            'total_reviews' => Shipment::whereNotNull('rating')->count(),
        ];

        $recentShipments = Shipment::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentShipments'));
    }

    /**
     * Daftar Semua Pengiriman (Admin)
     */
    public function adminShipments(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $query = Shipment::with('user');

        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search berdasarkan tracking number
        if ($request->has('search') && $request->search != '') {
            $query->where('tracking_number', 'like', '%' . $request->search . '%');
        }

        $shipments = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.shipments', compact('shipments'));
    }

    /**
     * Update Status oleh Admin
     */
    public function adminUpdateStatus(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'status'      => 'required|in:created,paid,picked_up,in_transit,delivered,cancelled',
            'description' => 'required|string',
            'location'    => 'nullable|string',
        ]);

        $shipment = Shipment::findOrFail($id);
        $shipment->update(['status' => $request->status]);

        ShipmentHistory::create([
            'shipment_id' => $shipment->id,
            'status'      => $request->status,
            'description' => $request->description,
            'location'    => $request->location ?: 'Admin Panel',
        ]);

        return back()->with('success', 'Status pengiriman berhasil diupdate!');
    }
}
