<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\Branch;
use App\Models\Rate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Tampilkan halaman laporan
     */
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        // Filter tanggal
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Statistik pengiriman
        $totalShipments = Shipment::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalRevenue = Shipment::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->sum('total_cost');

        // Status breakdown
        $statusBreakdown = Shipment::whereBetween('created_at', [$startDate, $endDate])
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        // Revenue per hari (untuk chart)
        $revenuePerDay = Shipment::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_cost) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Shipments per kota tujuan
        $shipmentsByCity = Shipment::whereBetween('created_at', [$startDate, $endDate])
            ->select('destination_city', DB::raw('count(*) as count'))
            ->groupBy('destination_city')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // Top customers
        $topCustomers = Shipment::whereBetween('created_at', [$startDate, $endDate])
            ->select('user_id', DB::raw('count(*) as count'), DB::raw('SUM(total_cost) as total'))
            ->groupBy('user_id')
            ->orderByDesc('count')
            ->limit(10)
            ->with('user')
            ->get();

        // Recent shipments
        $recentShipments = Shipment::whereBetween('created_at', [$startDate, $endDate])
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('admin.reports.index', compact(
            'startDate',
            'endDate',
            'totalShipments',
            'totalRevenue',
            'statusBreakdown',
            'revenuePerDay',
            'shipmentsByCity',
            'topCustomers',
            'recentShipments'
        ));
    }

    /**
     * Export laporan ke Excel
     */
    public function exportExcel(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $shipments = Shipment::whereBetween('created_at', [$startDate, $endDate])
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        $fileName = "Laporan_Pengiriman_{$startDate}_{$endDate}.csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ];

        $callback = function () use ($shipments) {
            $file = fopen('php://output', 'w');

            // Header
            fputcsv($file, [
                'No. Resi',
                'Customer',
                'Email',
                'Kota Asal',
                'Kota Tujuan',
                'Berat (Kg)',
                'Total Biaya',
                'Status',
                'Tanggal'
            ]);

            // Data
            foreach ($shipments as $shipment) {
                fputcsv($file, [
                    $shipment->tracking_number,
                    $shipment->user->name ?? '-',
                    $shipment->user->email ?? '-',
                    $shipment->origin_city,
                    $shipment->destination_city,
                    $shipment->weight,
                    $shipment->total_cost,
                    $shipment->status,
                    $shipment->created_at->format('d M Y H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export laporan ke PDF
     */
    public function exportPdf(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $totalShipments = Shipment::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalRevenue = Shipment::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->sum('total_cost');

        $statusBreakdown = Shipment::whereBetween('created_at', [$startDate, $endDate])
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        $shipments = Shipment::whereBetween('created_at', [$startDate, $endDate])
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = Pdf::loadView('admin.reports.pdf', compact(
            'startDate',
            'endDate',
            'totalShipments',
            'totalRevenue',
            'statusBreakdown',
            'shipments'
        ));

        return $pdf->download("Laporan_Pengiriman_{$startDate}_{$endDate}.pdf");
    }
}