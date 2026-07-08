@extends('layouts.app')

@section('title', 'Dashboard Admin - TirtaX')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">

        {{-- Header dengan Tombol Laporan --}}
        <div class="bg-gradient-to-r from-navy-900 to-navy-800 rounded-2xl shadow-xl p-8 mb-8 text-white">
            <div class="flex justify-between items-start flex-wrap gap-4">
                <div>
                    <div class="flex items-center gap-4 mb-2">
                        <h1 class="text-3xl font-bold">Dashboard Admin TirtaX! 👋</h1>
                        <a href="{{ route('admin.reports.index') }}"
                            class="px-4 py-2 bg-sky-300 text-navy-950 rounded-lg font-semibold hover:bg-sky-200 transition shadow-md flex items-center gap-2 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Laporan
                        </a>
                    </div>
                    <p class="text-sky-200">Monitor semua pengiriman, revenue, dan rating customer.</p>
                </div>
            </div>
        </div>

        {{-- Statistik Utama --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow p-5 border-2 border-navy-200">
                <p class="text-sm text-gray-500 font-medium">Total Pengiriman</p>
                <p class="text-3xl font-bold text-navy-950 mt-1">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-5 border-2 border-green-200">
                <p class="text-sm text-gray-500 font-medium">Total Revenue</p>
                <p class="text-2xl font-bold text-green-900 mt-1">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-5 border-2 border-navy-300">
                <p class="text-sm text-gray-500 font-medium">Dalam Proses</p>
                <p class="text-3xl font-bold text-navy-900 mt-1">{{ $stats['process'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-5 border-2 border-green-200">
                <p class="text-sm text-gray-500 font-medium">Selesai</p>
                <p class="text-3xl font-bold text-green-900 mt-1">{{ $stats['delivered'] }}</p>
            </div>
        </div>

        {{-- Status Breakdown --}}
        <div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-8">
            <div class="bg-white rounded-xl shadow p-4 border border-sky-200">
                <p class="text-xs text-sky-600 font-medium">Menunggu Bayar</p>
                <p class="text-2xl font-bold text-navy-900 mt-1">{{ $stats['pending'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4 border border-sky-200">
                <p class="text-xs text-sky-600 font-medium">Sudah Dibayar</p>
                <p class="text-2xl font-bold text-navy-900 mt-1">{{ $stats['paid'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4 border border-sky-200">
                <p class="text-xs text-sky-600 font-medium">Diambil Kurir</p>
                <p class="text-2xl font-bold text-navy-900 mt-1">{{ $stats['picked_up'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4 border border-green-200">
                <p class="text-xs text-green-600 font-medium">Selesai</p>
                <p class="text-2xl font-bold text-green-900 mt-1">{{ $stats['delivered'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4 border border-red-200">
                <p class="text-xs text-red-600 font-medium">Dibatalkan</p>
                <p class="text-2xl font-bold text-red-900 mt-1">{{ $stats['cancelled'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4 border border-yellow-200">
                <p class="text-xs text-yellow-600 font-medium">⭐ Rata-rata Rating</p>
                <p class="text-2xl font-bold text-yellow-700 mt-1">{{ $stats['avg_rating'] }}</p>
                <p class="text-xs text-gray-500">{{ $stats['total_reviews'] }} ulasan</p>
            </div>
        </div>

        {{-- Pengiriman Terbaru --}}
        <div class="bg-white rounded-2xl shadow-lg border border-sky-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-sky-100 bg-sky-50 flex justify-between items-center">
                <h2 class="text-xl font-bold text-navy-950">📦 Pengiriman Terbaru</h2>
                <a href="{{ route('admin.shipments') }}"
                    class="text-navy-700 hover:text-navy-900 font-semibold text-sm">Lihat Semua →</a>
            </div>

            @if($recentShipments->isEmpty())
                <div class="p-12 text-center">
                    <p class="text-gray-500">Belum ada pengiriman di sistem.</p>
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
                                <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">Rating</th>
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
                                    <td class="px-6 py-4">
                                        @if($shipment->rating)
                                            <div class="flex items-center gap-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= $shipment->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                        </path>
                                                    </svg>
                                                @endfor
                                                <span class="text-xs text-gray-500 ml-1">({{ $shipment->rating }})</span>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400">Belum dinilai</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Menu Cepat --}}
        <div class="mt-8">
            <h2 class="text-xl font-bold text-navy-950 mb-4">Menu Cepat</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('admin.shipments') }}"
                    class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition border-2 border-transparent hover:border-sky-300 group">
                    <div
                        class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-blue-200 transition">
                        <svg class="w-6 h-6 text-navy-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-navy-950 mb-1">Kelola Pengiriman</h3>
                    <p class="text-sm text-gray-500">Lihat & update semua pengiriman</p>
                </a>

                <a href="{{ route('admin.rates.index') }}"
                    class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition border-2 border-transparent hover:border-sky-300 group">
                    <div
                        class="w-12 h-12 bg-sky-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-sky-200 transition">
                        <svg class="w-6 h-6 text-navy-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-navy-950 mb-1">Master Tarif</h3>
                    <p class="text-sm text-gray-500">Kelola tarif pengiriman</p>
                </a>

                <a href="{{ route('admin.branches.index') }}"
                    class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition border-2 border-transparent hover:border-sky-300 group">
                    <div
                        class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-indigo-200 transition">
                        <svg class="w-6 h-6 text-navy-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-navy-950 mb-1">Master Cabang</h3>
                    <p class="text-sm text-gray-500">Kelola cabang operasional</p>
                </a>

                <a href="{{ route('admin.users.index') }}"
                    class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition border-2 border-transparent hover:border-sky-300 group">
                    <div
                        class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-purple-200 transition">
                        <svg class="w-6 h-6 text-navy-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-navy-950 mb-1">Master User</h3>
                    <p class="text-sm text-gray-500">Kelola akun pengguna</p>
                </a>

                <a href="/"
                    class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition border-2 border-transparent hover:border-sky-300 group">
                    <div
                        class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-blue-200 transition">
                        <svg class="w-6 h-6 text-navy-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-navy-950 mb-1">Lihat Website</h3>
                    <p class="text-sm text-gray-500">Tampilan website customer</p>
                </a>

                <a href="{{ route('admin.audit-logs.index') }}"
                    class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition border-2 border-transparent hover:border-sky-300 group">
                    <div
                        class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-red-200 transition">
                        <svg class="w-6 h-6 text-navy-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-navy-950 mb-1">Audit Log</h3>
                    <p class="text-sm text-gray-500">Pantau aktivitas sistem</p>
                </a>

                <a href="{{ route('admin.reports.index') }}"
                    class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition border-2 border-transparent hover:border-sky-300 group">
                    <div
                        class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-green-200 transition">
                        <svg class="w-6 h-6 text-navy-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-navy-950 mb-1">Laporan & Rekap</h3>
                    <p class="text-sm text-gray-500">Analisis dan export data</p>
                </a>

                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition border-2 border-transparent hover:border-red-300 group">
                    <div
                        class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-red-200 transition">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-red-600 mb-1">Logout</h3>
                    <p class="text-sm text-gray-500">Keluar dari dashboard admin</p>
                </a>
            </div>
        </div>

        {{-- Form Logout --}}
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>
@endsection