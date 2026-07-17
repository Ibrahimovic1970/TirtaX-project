@extends('layouts.app')

@section('title', 'Riwayat Pengiriman - TirtaX')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="bg-gradient-to-r from-navy-900 to-navy-800 rounded-2xl shadow-xl p-8 mb-8 text-white">
        <div class="flex justify-between items-start flex-wrap gap-4">
            <div>
                <h1 class="text-3xl font-bold mb-2">📦 Riwayat Pengiriman Saya</h1>
                <p class="text-sky-200">Kelola dan lacak semua paket Anda dalam satu tempat.</p>
            </div>
            <a href="{{ route('shipments.create') }}" class="px-6 py-3 bg-sky-300 text-navy-950 rounded-lg font-semibold hover:bg-sky-200 transition shadow-md flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Kirim Paket Baru
            </a>
        </div>
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

    {{-- Tabel Pengiriman --}}
    <div class="bg-white rounded-2xl shadow-lg border border-sky-100 overflow-hidden">
        @if($shipments->isEmpty())
            <div class="p-12 text-center">
                <div class="w-20 h-20 bg-sky-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-navy-950 mb-2">Belum ada pengiriman</h3>
                <p class="text-gray-500 mb-4">Silakan buat pengiriman pertama Anda.</p>
                <a href="{{ route('shipments.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-navy-800 text-white rounded-lg hover:bg-navy-900 transition font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Kirim Paket Sekarang
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-sky-100">
                    <thead class="bg-navy-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">No. Resi</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">Tujuan</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">Penerima</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">Biaya</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-navy-700 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-sky-100">
                        @foreach($shipments as $shipment)
                        <tr class="hover:bg-sky-50 transition">
                            <td class="px-6 py-4">
                                <div>
                                    <a href="{{ route('shipments.detail', $shipment->id) }}" class="text-sm font-bold text-navy-700 hover:text-navy-900">
                                        {{ $shipment->tracking_number }}
                                    </a>
                                    <p class="text-xs text-gray-500">{{ $shipment->created_at->format('d M Y, H:i') }} WIB</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $shipment->destination_city }}</div>
                                <div class="text-xs text-gray-500">Dari: {{ $shipment->origin_city }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $shipment->receiver_name ?? '-' }}</div>
                                <div class="text-xs text-gray-500">{{ $shipment->receiver_phone ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-navy-900">Rp {{ number_format($shipment->total_cost, 0, ',', '.') }}</div>
                                <div class="text-xs text-gray-500">{{ $shipment->weight }} Kg</div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusConfig = [
                                        'created' => ['bg-yellow-100', 'text-yellow-800', 'Menunggu Pembayaran'],
                                        'pending' => ['bg-blue-100', 'text-blue-800', 'Pembayaran Pending'],
                                        'paid' => ['bg-green-100', 'text-green-800', 'Sudah Dibayar'],
                                        'picked_up' => ['bg-indigo-100', 'text-indigo-800', 'Diambil Kurir'],
                                        'in_transit' => ['bg-purple-100', 'text-purple-800', 'Dalam Perjalanan'],
                                        'delivered' => ['bg-emerald-100', 'text-emerald-800', 'Terkirim'],
                                        'cancelled' => ['bg-red-100', 'text-red-800', 'Dibatalkan'],
                                    ];
                                    $config = $statusConfig[$shipment->status] ?? $statusConfig['created'];
                                @endphp
                                <span class="px-3 py-1.5 inline-flex items-center gap-1.5 text-xs font-bold rounded-full {{ $config[0] }} {{ $config[1] }}">
                                    @if($shipment->status === 'created')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    @elseif($shipment->status === 'paid')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    @endif
                                    {{ $config[2] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('shipments.detail', $shipment->id) }}"
                                       class="px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs font-semibold hover:bg-blue-100 transition">
                                        Detail
                                    </a>
                                    <a href="{{ route('tracking.show', $shipment->tracking_number) }}"
                                       class="px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-semibold hover:bg-indigo-100 transition">
                                        Lacak
                                    </a>

                                    @if($shipment->status === 'created' || $shipment->status === 'pending')
                                        <a href="{{ route('shipments.payment', $shipment->id) }}"
                                           class="px-3 py-1.5 bg-orange-500 text-white rounded-lg text-xs font-semibold hover:bg-orange-600 transition">
                                            Bayar
                                        </a>
                                        <form action="{{ route('shipments.cancel', $shipment->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-3 py-1.5 bg-red-50 text-red-700 rounded-lg text-xs font-semibold hover:bg-red-100 transition">
                                                Batal
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
