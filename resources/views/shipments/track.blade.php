@extends('layouts.app')

@section('title', 'Lacak Resi - ' . ($shipment->tracking_number ?? 'TirtaX'))

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-12">

        @if(isset($error))
            {{-- Error: Resi tidak ditemukan --}}
            <div class="bg-red-50 border-l-4 border-red-500 rounded-r-xl p-6 mb-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <div>
                        <h3 class="font-bold text-red-900">Resi Tidak Ditemukan</h3>
                        <p class="text-red-700 mt-1">{{ $error }}</p>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <a href="/"
                    class="inline-block px-6 py-3 bg-navy-800 text-white font-semibold rounded-lg hover:bg-navy-900 transition">
                    ← Kembali ke Beranda
                </a>
            </div>
        @elseif(isset($shipment))
            {{-- Header --}}
            <div class="bg-white rounded-2xl shadow-lg border border-sky-100 p-6 mb-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">NOMOR RESI</p>
                        <h1 class="text-3xl font-bold text-navy-900">{{ $shipment->tracking_number }}</h1>
                    </div>
                    @php
                        $statusConfig = [
                            'created' => ['bg-sky-100', 'text-navy-800', 'MENUNGGU PEMBAYARAN'],
                            'paid' => ['bg-navy-100', 'text-navy-900', 'SUDAH DIBAYAR'],
                            'picked_up' => ['bg-blue-100', 'text-blue-800', 'DIAMBIL KURIER'],
                            'in_transit' => ['bg-indigo-100', 'text-indigo-800', 'DALAM PERJALANAN'],
                            'delivered' => ['bg-green-100', 'text-green-800', 'TERKIRIM'],
                            'cancelled' => ['bg-red-100', 'text-red-800', 'DIBATALKAN'],
                        ];
                        $config = $statusConfig[$shipment->status] ?? $statusConfig['created'];
                    @endphp
                    <span class="px-4 py-2 text-sm font-bold rounded-full {{ $config[0] }} {{ $config[1] }}">
                        {{ $config[2] }}
                    </span>
                </div>

                {{-- Info Penerima --}}
                <div class="bg-sky-50 rounded-xl p-4 border border-sky-100">
                    <h3 class="font-semibold text-navy-950 mb-3">Informasi Penerima</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                        <div>
                            <span class="text-gray-500">Nama:</span>
                            <p class="font-medium text-navy-900">{{ $shipment->receiver_name }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">No. HP:</span>
                            <p class="font-medium text-navy-900">{{ $shipment->receiver_phone }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <span class="text-gray-500">Alamat:</span>
                            <p class="font-medium text-navy-900">{{ $shipment->receiver_address }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Kota Tujuan:</span>
                            <p class="font-medium text-navy-900">{{ $shipment->destination_city }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Berat:</span>
                            <p class="font-medium text-navy-900">{{ $shipment->weight }} Kg</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Timeline Status --}}
            <div class="bg-white rounded-2xl shadow-lg border border-sky-100 p-6 mb-6">
                <h2 class="text-xl font-bold text-navy-950 mb-6">Riwayat Pengiriman</h2>

                <div class="relative">
                    @php
                        $histories = $shipment->histories()->orderBy('created_at', 'desc')->get();
                    @endphp

                    @forelse($histories as $index => $history)
                        <div
                            class="relative pl-12 pb-8 {{ $index !== $histories->count() - 1 ? 'border-l-2 border-sky-200' : '' }}">
                            {{-- Icon --}}
                            <div class="absolute left-0 top-0 w-8 h-8 rounded-full flex items-center justify-center 
                                                {{ $index === 0 ? 'bg-navy-800' : 'bg-sky-200' }}">
                                @if($index === 0)
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                @else
                                    <div class="w-3 h-3 bg-white rounded-full"></div>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="bg-sky-50 rounded-lg p-4 ml-4 border border-sky-100">
                                <div class="flex justify-between items-start mb-2 flex-wrap gap-2">
                                    <h3 class="font-bold text-navy-950">{{ $history->description }}</h3>
                                    @php
                                        $statusLabels = [
                                            'created' => 'Pesanan Dibuat',
                                            'paid' => 'Pembayaran Dikonfirmasi',
                                            'picked_up' => 'Diambil Kurir',
                                            'in_transit' => 'Dalam Perjalanan',
                                            'delivered' => 'Diterima',
                                            'cancelled' => 'Dibatalkan',
                                        ];
                                        $label = $statusLabels[$history->status] ?? $history->status;
                                    @endphp
                                    <span class="text-xs px-2 py-1 bg-navy-100 text-navy-800 rounded font-medium">
                                        {{ $label }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mb-1">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $history->location }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $history->created_at->format('d M Y, H:i') }} WIB
                                </p>

                                {{-- TAMPILKAN FOTO POD --}}
                                @if($history->photo_url)
                                    <div class="mt-4">
                                        <p class="text-sm font-semibold text-navy-900 mb-2">📷 Foto Bukti Pengiriman:</p>
                                        <div class="border-2 border-sky-200 rounded-lg overflow-hidden inline-block">
                                            <img src="{{ $history->photo_url }}" alt="Bukti Pengiriman"
                                                class="max-w-md max-h-96 object-contain" onclick="this.requestFullscreen()">
                                        </div>
                                        <p class="text-xs text-gray-500 mt-2">* Klik foto untuk fullscreen</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-gray-500">Belum ada riwayat pengiriman.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-4 justify-center flex-wrap">
                <a href="/"
                    class="px-6 py-3 bg-navy-800 text-white font-semibold rounded-lg hover:bg-navy-900 transition shadow-md">
                    ← Kembali ke Beranda
                </a>
                @auth
                    <a href="{{ route('shipments.my') }}"
                        class="px-6 py-3 bg-sky-600 text-white font-semibold rounded-lg hover:bg-sky-700 transition shadow-md">
                        Riwayat Pesanan Saya
                    </a>
                @endauth
            </div>
        @endif
    </div>
@endsection