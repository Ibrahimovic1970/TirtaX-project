@extends('layouts.app')

@section('title', 'Detail Pengiriman - TirtaX')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('shipments.my') }}" class="inline-flex items-center gap-2 text-navy-700 hover:text-navy-900 font-semibold">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Riwayat Pengiriman
        </a>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 rounded-r-xl p-4 mb-6 shadow-md">
            <p class="text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 rounded-r-xl p-4 mb-6 shadow-md">
            <p class="text-red-700 font-medium">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Header --}}
    <div class="bg-gradient-to-r from-navy-900 to-navy-800 rounded-2xl shadow-xl p-8 mb-8 text-white">
        <div class="flex justify-between items-start flex-wrap gap-4">
            <div>
                <p class="text-sm text-sky-200 mb-1">NOMOR RESI</p>
                <h1 class="text-3xl font-bold mb-2">{{ $shipment->tracking_number }}</h1>
                <p class="text-sm text-gray-300">Dibuat pada {{ $shipment->created_at->format('d M Y, H:i') }} WIB</p>
            </div>
            <div>
                @php
                    $statusConfig = [
                        'created' => ['bg-yellow-100', 'text-yellow-800', 'MENUNGGU PEMBAYARAN'],
                        'pending' => ['bg-blue-100', 'text-blue-800', 'PEMBAYARAN PENDING'],
                        'paid' => ['bg-green-100', 'text-green-800', 'SUDAH DIBAYAR'],
                        'picked_up' => ['bg-indigo-100', 'text-indigo-800', 'DIAMBIL KURIR'],
                        'in_transit' => ['bg-purple-100', 'text-purple-800', 'DALAM PERJALANAN'],
                        'delivered' => ['bg-emerald-100', 'text-emerald-800', 'TERKIRIM'],
                        'cancelled' => ['bg-red-100', 'text-red-800', 'DIBATALKAN'],
                    ];
                    $config = $statusConfig[$shipment->status] ?? $statusConfig['created'];
                @endphp
                <span class="px-4 py-2 inline-flex items-center gap-2 text-sm font-bold rounded-full {{ $config[0] }} {{ $config[1] }}">
                    {{ $config[2] }}
                </span>
            </div>
        </div>
    </div>

    {{-- Tombol Aksi --}}
    <div class="flex flex-wrap gap-3 mb-8">
        @if($shipment->status === 'created' || $shipment->status === 'pending')
            <a href="{{ route('shipments.payment', $shipment->id) }}"
               class="px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold rounded-lg hover:from-orange-600 hover:to-orange-700 transition shadow-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Bayar Sekarang
            </a>
        @endif
        <a href="{{ route('tracking.show', $shipment->tracking_number) }}"
           class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold rounded-lg hover:from-blue-700 hover:to-blue-800 transition shadow-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
            </svg>
            Lacak Paket
        </a>
        @if($shipment->status === 'created' || $shipment->status === 'pending')
            <form action="{{ route('shipments.cancel', $shipment->id) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white font-bold rounded-lg hover:from-red-700 hover:to-red-800 transition shadow-lg flex items-center gap-2"
                        onclick="return confirm('Yakin ingin membatalkan pesanan ini?')">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Batalkan Pesanan
                </button>
            </form>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        {{-- Info Pengirim --}}
        <div class="bg-white rounded-2xl shadow-lg border border-sky-100 p-6">
            <h2 class="text-xl font-bold text-navy-950 mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-navy-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Info Pengirim
            </h2>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Nama</p>
                    <p class="font-semibold text-navy-950">{{ Auth::user()->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Email</p>
                    <p class="font-semibold text-navy-950">{{ Auth::user()->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Kota Asal</p>
                    <p class="font-semibold text-navy-950">{{ $shipment->origin_city }}</p>
                </div>
            </div>
        </div>

        {{-- Info Penerima --}}
        <div class="bg-white rounded-2xl shadow-lg border border-sky-100 p-6">
            <h2 class="text-xl font-bold text-navy-950 mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-navy-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Info Penerima
            </h2>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Nama</p>
                    <p class="font-semibold text-navy-950">{{ $shipment->receiver_name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">No. HP</p>
                    <p class="font-semibold text-navy-950">{{ $shipment->receiver_phone ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Alamat</p>
                    <p class="font-semibold text-navy-950">{{ $shipment->receiver_address ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Kota Tujuan</p>
                    <p class="font-semibold text-navy-950">{{ $shipment->destination_city }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Paket & Biaya --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        {{-- Detail Paket --}}
        <div class="bg-white rounded-2xl shadow-lg border border-sky-100 p-6">
            <h2 class="text-xl font-bold text-navy-950 mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-navy-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                Detail Paket
            </h2>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Berat</p>
                    <p class="font-semibold text-navy-950">{{ $shipment->weight }} Kg</p>
                </div>
            </div>
        </div>

        {{-- Rincian Biaya --}}
        <div class="bg-white rounded-2xl shadow-lg border border-sky-100 p-6 lg:col-span-2">
            <h2 class="text-xl font-bold text-navy-950 mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-navy-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Rincian Biaya
            </h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                    <span class="text-gray-600">Ongkos Kirim</span>
                    <span class="font-semibold text-navy-950">Rp {{ number_format($shipment->total_cost, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center py-3 bg-navy-50 rounded-lg px-4">
                    <span class="text-lg font-bold text-navy-950">Total Pembayaran</span>
                    <span class="text-2xl font-bold text-accent-600">Rp {{ number_format($shipment->total_cost, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Status Pembayaran --}}
    <div class="bg-white rounded-2xl shadow-lg border border-sky-100 p-6 mb-8">
        <h2 class="text-xl font-bold text-navy-950 mb-6 flex items-center gap-2">
            <svg class="w-6 h-6 text-navy-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Status Pembayaran
        </h2>
        <div class="flex items-center gap-4">
            <span class="px-4 py-2 inline-flex items-center gap-2 text-sm font-bold rounded-full {{ $config[0] }} {{ $config[1] }}">
                {{ $config[2] }}
            </span>
            @if($shipment->status === 'created')
                <span class="text-sm text-gray-500">Silakan lakukan pembayaran untuk memproses pengiriman</span>
            @elseif($shipment->status === 'paid')
                <span class="text-sm text-green-600">Pembayaran telah dikonfirmasi</span>
            @endif
        </div>
    </div>
</div>
@endsection
