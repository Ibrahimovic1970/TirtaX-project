@extends('layouts.app')

@section('title', 'Laporan & Rekap - TirtaX')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-navy-900 to-navy-800 rounded-2xl shadow-xl p-8 mb-8 text-white">
            <div class="flex justify-between items-start flex-wrap gap-4">
                <div>
                    <h1 class="text-3xl font-bold mb-2">📊 Laporan & Rekap</h1>
                    <p class="text-sky-200">Analisis dan export data pengiriman TirtaX.</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.reports.export-excel', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
                        class="px-6 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition shadow-md flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Export Excel
                    </a>
                    <a href="{{ route('admin.reports.export-pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
                        class="px-6 py-3 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition shadow-md flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Export PDF
                    </a>
                </div>
            </div>
        </div>

        {{-- Filter Tanggal --}}
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border border-sky-100">
            <form method="GET" action="{{ route('admin.reports.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-navy-900 mb-2">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ $startDate }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900">
                </div>
                <div>
                    <label class="block text-sm font-medium text-navy-900 mb-2">Tanggal Akhir</label>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900">
                </div>
                <div class="flex items-end">
                    <button type="submit"
                        class="w-full px-6 py-3 bg-navy-800 text-white rounded-lg hover:bg-navy-900 transition font-semibold flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Filter
                    </button>
                </div>
                <div class="flex items-end">
                    <a href="{{ route('admin.reports.index') }}"
                        class="w-full px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-semibold text-center">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Statistik Utama --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 border-2 border-navy-200">
                <p class="text-sm text-gray-500 font-medium mb-1">Total Pengiriman</p>
                <p class="text-3xl font-bold text-navy-950">{{ number_format($totalShipments) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 border-2 border-green-200">
                <p class="text-sm text-gray-500 font-medium mb-1">Total Revenue</p>
                <p class="text-2xl font-bold text-green-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 border-2 border-blue-200">
                <p class="text-sm text-gray-500 font-medium mb-1">Rata-rata per Pengiriman</p>
                <p class="text-2xl font-bold text-blue-900">Rp
                    {{ $totalShipments > 0 ? number_format($totalRevenue / $totalShipments, 0, ',', '.') : 0 }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 border-2 border-purple-200">
                <p class="text-sm text-gray-500 font-medium mb-1">Periode</p>
                <p class="text-lg font-bold text-purple-900">{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}</p>
                <p class="text-lg font-bold text-purple-900">s/d {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
            </div>
        </div>

        {{-- Charts --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            {{-- Chart Revenue per Hari --}}
            <div class="bg-white rounded-2xl shadow-lg border border-sky-100 p-6">
                <h3 class="text-xl font-bold text-navy-950 mb-4">📈 Revenue per Hari</h3>
                <canvas id="revenueChart" height="200"></canvas>
            </div>

            {{-- Chart Status Breakdown --}}
            <div class="bg-white rounded-2xl shadow-lg border border-sky-100 p-6">
                <h3 class="text-xl font-bold text-navy-950 mb-4">📊 Status Pengiriman</h3>
                <canvas id="statusChart" height="200"></canvas>
            </div>
        </div>

        {{-- Top Kota & Top Customers --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            {{-- Top Kota Tujuan --}}
            <div class="bg-white rounded-2xl shadow-lg border border-sky-100 p-6">
                <h3 class="text-xl font-bold text-navy-950 mb-4">🏙️ Top 10 Kota Tujuan</h3>
                <div class="space-y-3">
                    @forelse($shipmentsByCity as $city)
                        <div class="flex items-center justify-between p-3 bg-sky-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <span
                                    class="w-8 h-8 bg-navy-800 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                    {{ $loop->iteration }}
                                </span>
                                <span class="font-semibold text-navy-950">{{ $city->destination_city }}</span>
                            </div>
                            <span class="px-3 py-1 bg-navy-100 text-navy-800 rounded-full text-sm font-bold">
                                {{ $city->count }} pengiriman
                            </span>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Tidak ada data</p>
                    @endforelse
                </div>
            </div>

            {{-- Top Customers --}}
            <div class="bg-white rounded-2xl shadow-lg border border-sky-100 p-6">
                <h3 class="text-xl font-bold text-navy-950 mb-4">👥 Top 10 Customer</h3>
                <div class="space-y-3">
                    @forelse($topCustomers as $customer)
                        <div class="flex items-center justify-between p-3 bg-sky-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <span
                                    class="w-8 h-8 bg-sky-600 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                    {{ $loop->iteration }}
                                </span>
                                <div>
                                    <p class="font-semibold text-navy-950">{{ $customer->user->name ?? 'Unknown' }}</p>
                                    <p class="text-xs text-gray-500">{{ $customer->count }} pengiriman</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-bold">
                                Rp {{ number_format($customer->total, 0, ',', '.') }}
                            </span>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Tidak ada data</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Recent Shipments --}}
        <div class="bg-white rounded-2xl shadow-lg border border-sky-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-sky-100 bg-sky-50">
                <h2 class="text-xl font-bold text-navy-950">📦 Pengiriman Terbaru</h2>
            </div>

            @if($recentShipments->isEmpty())
                <div class="p-12 text-center">
                    <p class="text-gray-500">Tidak ada pengiriman pada periode ini.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-sky-100">
                        <thead class="bg-navy-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">No. Resi</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">Tujuan</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">Biaya</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-sky-100">
                            @foreach($recentShipments as $shipment)
                                <tr class="hover:bg-sky-50 transition">
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-bold text-navy-700">{{ $shipment->tracking_number }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-navy-950">{{ $shipment->user->name ?? '-' }}</div>
                                        <div class="text-xs text-gray-500">{{ $shipment->user->email ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $shipment->destination_city }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-navy-950">Rp
                                        {{ number_format($shipment->total_cost, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusConfig = [
                                                'created' => ['bg-sky-100', 'text-navy-800', 'Menunggu Bayar'],
                                                'paid' => ['bg-navy-100', 'text-navy-900', 'Sudah Dibayar'],
                                                'picked_up' => ['bg-blue-100', 'text-blue-800', 'Diambil Kurir'],
                                                'in_transit' => ['bg-indigo-100', 'text-indigo-800', 'Dalam Perjalanan'],
                                                'delivered' => ['bg-green-100', 'text-green-800', 'Selesai'],
                                                'cancelled' => ['bg-red-100', 'text-red-800', 'Dibatalkan'],
                                            ];
                                            $config = $statusConfig[$shipment->status] ?? $statusConfig['created'];
                                        @endphp
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $config[0] }} {{ $config[1] }}">
                                            {{ $config[2] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $shipment->created_at->format('d M Y, H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Revenue per Day Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueData = @json($revenuePerDay);

        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: revenueData.map(item => item.date),
                datasets: [{
                    label: 'Revenue (Rp)',
                    data: revenueData.map(item => item.total),
                    borderColor: '#1e40af',
                    backgroundColor: 'rgba(30, 64, 175, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function (value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

        // Status Breakdown Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusData = @json($statusBreakdown);

        const statusLabels = {
            'created': 'Menunggu Bayar',
            'paid': 'Sudah Dibayar',
            'picked_up': 'Diambil Kurir',
            'in_transit': 'Dalam Perjalanan',
            'delivered': 'Selesai',
            'cancelled': 'Dibatalkan'
        };

        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(statusData).map(key => statusLabels[key] || key),
                datasets: [{
                    data: Object.values(statusData),
                    backgroundColor: [
                        '#bae6fd',
                        '#dbe4ff',
                        '#bfdbfe',
                        '#e0e7ff',
                        '#bbf7d0',
                        '#fecaca'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
@endsection