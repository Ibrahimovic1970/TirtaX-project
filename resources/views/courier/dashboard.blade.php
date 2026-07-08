@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-8">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-navy-900 to-navy-800 rounded-2xl shadow-xl p-8 mb-8 text-white">
            <h1 class="text-3xl font-bold mb-2">Dashboard Kurir TirtaX! 👋</h1>
            <p class="text-sky-200">Kelola dan antar semua paket yang menjadi tugas Anda.</p>
        </div>

        {{-- Alert Success --}}
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 rounded-r-xl p-4 mb-6 shadow-md">
                <p class="text-green-700 font-medium">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Statistics --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 border-2 border-navy-200">
                <p class="text-sm text-gray-500 font-medium mb-1">Total Tugas Antar</p>
                <p class="text-4xl font-bold text-navy-950">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 border-2 border-sky-200">
                <p class="text-sm text-sky-600 font-medium mb-1">Siap Diambil</p>
                <p class="text-4xl font-bold text-navy-900">{{ $stats['paid'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 border-2 border-blue-200">
                <p class="text-sm text-blue-600 font-medium mb-1">Sudah Diambil</p>
                <p class="text-4xl font-bold text-blue-900">{{ $stats['picked_up'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 border-2 border-indigo-200">
                <p class="text-sm text-indigo-600 font-medium mb-1">Menuju Tujuan</p>
                <p class="text-4xl font-bold text-indigo-900">{{ $stats['in_transit'] }}</p>
            </div>
        </div>

        {{-- Task List --}}
        <div class="bg-white rounded-2xl shadow-lg border border-sky-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-sky-100 bg-sky-50">
                <h2 class="text-xl font-bold text-navy-950">📦 Daftar Paket Customer untuk Diantar</h2>
                <p class="text-sm text-gray-500 mt-1">Paket yang sudah dibayar customer dan menunggu kurir</p>
            </div>

            @if($tasks->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-navy-950 mb-2">Tidak Ada Paket untuk Diantar</h3>
                    <p class="text-gray-500">Semua paket customer telah sampai. Kerja bagus! 🎉</p>
                </div>
            @else
                <div class="divide-y divide-sky-100">
                    @foreach($tasks as $task)
                        <div class="px-6 py-5 hover:bg-sky-50 transition">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="text-sm font-bold text-navy-700 bg-sky-100 px-3 py-1 rounded">
                                            {{ $task->tracking_number }}
                                        </span>
                                        @php
                                            $statusBadge = [
                                                'paid' => ['bg-sky-100', 'text-navy-800', '📦 SIAP DIAMBIL'],
                                                'picked_up' => ['bg-blue-100', 'text-blue-800', '🚚 SUDAH DIAMBIL'],
                                                'in_transit' => ['bg-indigo-100', 'text-indigo-800', '🛣️ MENUJU TUJUAN'],
                                            ];
                                            $badge = $statusBadge[$task->status] ?? $statusBadge['paid'];
                                        @endphp
                                        <span class="px-3 py-1.5 text-xs font-bold rounded-full {{ $badge[0] }} {{ $badge[1] }}">
                                            {{ $badge[2] }}
                                        </span>
                                    </div>

                                    <div class="space-y-1 text-sm">
                                        <p class="text-navy-950">
                                            <span class="font-semibold">👤 Penerima:</span>
                                            {{ $task->receiver_name }}
                                            <span class="text-gray-500">({{ $task->receiver_phone }})</span>
                                        </p>
                                        <p class="text-gray-700">
                                            <span class="font-semibold">📍 Alamat Antar:</span> {{ $task->receiver_address }}
                                        </p>
                                        <p class="text-gray-700">
                                            <span class="font-semibold">🎯 Kota Tujuan:</span> {{ $task->destination_city }}
                                            <span class="mx-2">•</span>
                                            <span class="font-semibold">️ Berat:</span> {{ $task->weight }} Kg
                                        </p>
                                    </div>
                                </div>

                                <div class="flex gap-2">
                                    <a href="tel:{{ $task->receiver_phone }}"
                                        class="px-4 py-2 border-2 border-navy-300 rounded-lg text-sm font-semibold text-navy-700 hover:bg-navy-50 transition">
                                        📞 Hubungi
                                    </a>
                                    <a href="{{ route('courier.shipment.detail', $task->id) }}"
                                        class="px-4 py-2 bg-navy-800 text-white rounded-lg text-sm font-semibold hover:bg-navy-900 transition shadow-md">
                                        ✏️ Update Status
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection