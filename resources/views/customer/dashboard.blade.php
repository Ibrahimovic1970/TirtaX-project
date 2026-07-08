@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-12">

        {{-- Welcome Banner --}}
        <div class="bg-gradient-to-r from-navy-900 to-navy-800 rounded-2xl shadow-xl p-8 mb-8 text-white">
            <h1 class="text-3xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
            <p class="text-sky-200">Kelola dan lacak semua pengiriman Anda dalam satu tempat.</p>
        </div>

        {{-- Quick Actions --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <a href="{{ route('shipments.create') }}"
                class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition border-2 border-transparent hover:border-sky-300 group">
                <div
                    class="w-14 h-14 bg-sky-100 rounded-full flex items-center justify-center mb-4 group-hover:bg-sky-200 transition">
                    <svg class="w-7 h-7 text-navy-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-navy-950 mb-1">Pesan Pengiriman Baru</h3>
                <p class="text-sm text-gray-500">Buat pesanan pengiriman paket</p>
            </a>

            <a href="{{ route('shipments.my') }}"
                class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition border-2 border-transparent hover:border-sky-300 group">
                <div
                    class="w-14 h-14 bg-sky-100 rounded-full flex items-center justify-center mb-4 group-hover:bg-sky-200 transition">
                    <svg class="w-7 h-7 text-navy-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-navy-950 mb-1">Riwayat Pesanan Saya</h3>
                <p class="text-sm text-gray-500">Lihat semua pesanan pengiriman</p>
            </a>

            <a href="/"
                class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition border-2 border-transparent hover:border-sky-300 group">
                <div
                    class="w-14 h-14 bg-sky-100 rounded-full flex items-center justify-center mb-4 group-hover:bg-sky-200 transition">
                    <svg class="w-7 h-7 text-navy-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-navy-950 mb-1">Lacak Paket Saya</h3>
                <p class="text-sm text-gray-500">Cek status pengiriman paket</p>
            </a>
        </div>

        {{-- Statistics --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-xl shadow p-5 border-2 border-navy-200">
                <p class="text-sm text-gray-500 font-medium">Total Pesanan</p>
                <p class="text-3xl font-bold text-navy-950 mt-1">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-5 border-2 border-sky-200">
                <p class="text-sm text-sky-600 font-medium">Menunggu Bayar</p>
                <p class="text-3xl font-bold text-navy-900 mt-1">{{ $stats['pending'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-5 border-2 border-navy-300">
                <p class="text-sm text-navy-600 font-medium">Sedang Dikirim</p>
                <p class="text-3xl font-bold text-navy-950 mt-1">{{ $stats['process'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-5 border-2 border-green-200">
                <p class="text-sm text-green-600 font-medium">Sampai Tujuan</p>
                <p class="text-3xl font-bold text-green-900 mt-1">{{ $stats['delivered'] }}</p>
            </div>
        </div>

        {{-- Recent Shipments --}}
        <div class="bg-white rounded-2xl shadow-lg border border-sky-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-sky-100 flex justify-between items-center bg-sky-50">
                <h2 class="text-xl font-bold text-navy-950">📦 Pesanan Pengiriman Terbaru</h2>
                <a href="{{ route('shipments.my') }}" class="text-navy-700 hover:text-navy-900 font-semibold text-sm">Lihat
                    Semua →</a>
            </div>

            @if($recentShipments->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-20 h-20 bg-sky-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-navy-950 mb-2">Belum ada pesanan</h3>
                    <p class="text-gray-500 mb-6">Mulai pesan pengiriman paket pertama Anda.</p>
                    <a href="{{ route('shipments.create') }}"
                        class="inline-block bg-navy-800 text-white px-6 py-3 rounded-lg font-semibold hover:bg-navy-900 transition">
                        Pesan Pengiriman Pertama
                    </a>
                </div>
            @else
                <div class="divide-y divide-sky-100">
                    @foreach($recentShipments as $shipment)
                        <div class="px-6 py-4 hover:bg-sky-50 transition">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-1">
                                        <span class="text-sm font-bold text-navy-700">{{ $shipment->tracking_number }}</span>
                                        @php
                                            $statusConfig = [
                                                'created' => ['bg-sky-100', 'text-navy-800', 'Menunggu Pembayaran'],
                                                'paid' => ['bg-navy-100', 'text-navy-900', 'Sudah Dibayar'],
                                                'picked_up' => ['bg-blue-100', 'text-blue-800', 'Diambil Kurir'],
                                                'in_transit' => ['bg-indigo-100', 'text-indigo-800', 'Dalam Perjalanan'],
                                                'delivered' => ['bg-green-100', 'text-green-800', 'Sampai Tujuan'],
                                            ];
                                            $config = $statusConfig[$shipment->status] ?? $statusConfig['created'];
                                        @endphp
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $config[0] }} {{ $config[1] }}">
                                            {{ $config[2] }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600">
                                        Tujuan: <span class="font-semibold text-navy-900">{{ $shipment->destination_city }}</span> •
                                        Berat: {{ $shipment->weight }} Kg •
                                        Rp {{ number_format($shipment->total_cost, 0, ',', '.') }}
                                    </p>
                                </div>
                                <a href="{{ route('tracking.show', $shipment->tracking_number) }}"
                                    class="ml-4 px-4 py-2 text-sm font-semibold text-navy-700 bg-sky-100 rounded-lg hover:bg-sky-200 transition">
                                    Lacak
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection