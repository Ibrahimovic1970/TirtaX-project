<?php

namespace App\Http\Controllers;

use App\Models\Rate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RateController extends Controller
{
    /**
     * Tampilkan daftar tarif
     */
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $query = Rate::query();

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('origin_city', 'LIKE', '%' . $search . '%')
                    ->orWhere('destination_city', 'LIKE', '%' . $search . '%');
            });
        }

        // Filter service type
        if ($request->has('service_type') && $request->service_type != '') {
            $query->where('service_type', $request->service_type);
        }

        $rates = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.rates.index', compact('rates'));
    }

    /**
     * Tampilkan form tambah tarif
     */
    public function create()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        return view('admin.rates.create');
    }

    /**
     * Simpan tarif baru
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'origin_city' => 'required|string|max:100',
            'destination_city' => 'required|string|max:100',
            'price_per_kg' => 'required|numeric|min:0',
            'base_price' => 'required|numeric|min:0',
            'service_type' => 'required|in:REGULER,EKONOMI,EXPRESS,SAMEDAY',
            'estimation_days' => 'required|integer|min:1|max:30',
            'is_active' => 'nullable|boolean',
        ]);

        Rate::create([
            'origin_city' => $request->origin_city,
            'destination_city' => $request->destination_city,
            'price_per_kg' => $request->price_per_kg,
            'base_price' => $request->base_price,
            'service_type' => $request->service_type,
            'estimation_days' => $request->estimation_days,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.rates.index')
            ->with('success', 'Tarif berhasil ditambahkan!');
    }

    /**
     * Tampilkan form edit tarif
     */
    public function edit($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $rate = Rate::findOrFail($id);
        return view('admin.rates.edit', compact('rate'));
    }

    /**
     * Update tarif
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $rate = Rate::findOrFail($id);

        $request->validate([
            'origin_city' => 'required|string|max:100',
            'destination_city' => 'required|string|max:100',
            'price_per_kg' => 'required|numeric|min:0',
            'base_price' => 'required|numeric|min:0',
            'service_type' => 'required|in:REGULER,EKONOMI,EXPRESS,SAMEDAY',
            'estimation_days' => 'required|integer|min:1|max:30',
            'is_active' => 'nullable|boolean',
        ]);

        $rate->update([
            'origin_city' => $request->origin_city,
            'destination_city' => $request->destination_city,
            'price_per_kg' => $request->price_per_kg,
            'base_price' => $request->base_price,
            'service_type' => $request->service_type,
            'estimation_days' => $request->estimation_days,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.rates.index')
            ->with('success', 'Tarif berhasil diupdate!');
    }

    /**
     * Hapus tarif
     */
    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $rate = Rate::findOrFail($id);
        $rate->delete();

        return back()->with('success', 'Tarif berhasil dihapus!');
    }

    /**
     * API: Hitung ongkir otomatis (dipanggil via AJAX)
     */
    public function calculateShipping(Request $request)
    {
        $request->validate([
            'origin' => 'required|string',
            'destination' => 'required|string',
            'weight' => 'required|numeric|min:0.1',
            'service_type' => 'nullable|string|in:REGULER,EKONOMI,EXPRESS,SAMEDAY',
        ]);

        $serviceType = $request->service_type ?? 'REGULER';

        $rate = Rate::findByRoute(
            $request->origin,
            $request->destination,
            $serviceType
        );

        if (!$rate) {
            return response()->json([
                'success' => false,
                'message' => 'Tarif untuk rute ini tidak ditemukan. Silakan hubungi admin.',
            ], 404);
        }

        $totalCost = $rate->calculateCost($request->weight);

        return response()->json([
            'success' => true,
            'data' => [
                'origin' => $rate->origin_city,
                'destination' => $rate->destination_city,
                'weight' => $request->weight,
                'price_per_kg' => $rate->price_per_kg,
                'base_price' => $rate->base_price,
                'total_cost' => $totalCost,
                'service_type' => $rate->service_type,
                'estimation_days' => $rate->estimation_days,
            ],
        ]);
    }
}