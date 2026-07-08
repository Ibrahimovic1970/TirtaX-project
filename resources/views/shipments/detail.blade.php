@extends('layouts.app')

@section('title', 'Detail Pengiriman - ' . $shipment->tracking_number)

@section('content')
    <div class="max-w-5xl mx-auto px-4 py-8">

        {{-- Breadcrumb --}}
        <div class="mb-6">
            <a href="{{ route('shipments.my') }}"
                class="text-tirtax-600 hover:text-tirtax-700 font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Kembali ke Riwayat Pengiriman
            </a>
        </div>

        {{-- Alert --}}
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

        {{-- Header Card --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">NOMOR RESI</p>
                    <h1 class="text-3xl font-bold text-tirtax-600">{{ $shipment->tracking_number }}</h1>
                    <p class="text-sm text-gray-500 mt-1">Dibuat pada {{ $shipment->created_at->format('d M Y, H:i') }} WIB
                    </p>
                </div>
                @php
                    $statusConfig = [
                        'created' => ['bg-yellow-100', 'text-yellow-800', 'MENUNGGU PEMBAYARAN'],
                        'paid' => ['bg-blue-100', 'text-blue-800', 'MENUNGGU KURIER'],
                        'picked_up' => ['bg-purple-100', 'text-purple-800', 'DIAMBIL KURIER'],
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

                    {{-- Tombol Aksi --}}
        <div class="flex flex-wrap gap-3">
            @if($shipment->status === 'created')
                <a href="{{ route('payment.show', $shipment->id) }}" 
                   class="px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold rounded-lg hover:from-orange-600 hover:to-orange-700 transition shadow-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    Bayar Sekarang
                </a>
            @endif

            <a href="{{ url('/track/' . $shipment->tracking_number) }}" 
               class="px-6 py-3 bg-navy-800 text-white font-semibold rounded-lg hover:bg-navy-900 transition shadow-md flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Lacak Paket
            </a>

            @if(in_array($shipment->status, ['paid', 'picked_up', 'in_transit', 'delivered']))
                <a href="{{ route('shipments.invoice', $shipment->id) }}" 
                   class="px-6 py-3 bg-navy-700 text-white font-semibold rounded-lg hover:bg-navy-800 transition shadow-md flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download Invoice
                </a>
            @endif

            @if($shipment->status === 'created')
                <button onclick="openCancelModal()" 
                        class="px-6 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition shadow-md flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Batalkan Pesanan
                </button>
            @endif
        </div>

        {{-- Grid Info --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

            {{-- Info Pengirim --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6 text-tirtax-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Info Pengirim
                </h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500">Nama</p>
                        <p class="font-semibold text-gray-900">{{ $shipment->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Email</p>
                        <p class="font-semibold text-gray-900">{{ $shipment->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Kota Asal</p>
                        <p class="font-semibold text-gray-900">{{ $shipment->origin_city }}</p>
                    </div>
                </div>
            </div>

            {{-- Info Penerima --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6 text-tirtax-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Info Penerima
                </h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500">Nama</p>
                        <p class="font-semibold text-gray-900">{{ $shipment->receiver_name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">No. HP</p>
                        <p class="font-semibold text-gray-900">{{ $shipment->receiver_phone }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Alamat</p>
                        <p class="font-semibold text-gray-900">{{ $shipment->receiver_address }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Kota Tujuan</p>
                        <p class="font-semibold text-gray-900">{{ $shipment->destination_city }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detail Pengiriman & Biaya --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

            {{-- Detail Paket --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6 text-tirtax-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    Detail Paket
                </h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500">Berat</p>
                        <p class="font-semibold text-gray-900">{{ $shipment->weight }} Kg</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Kurir</p>
                        <p class="font-semibold text-gray-900">TirtaX Express</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Layanan</p>
                        <p class="font-semibold text-gray-900">Reguler</p>
                    </div>
                </div>
            </div>

            {{-- Rincian Biaya --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6 text-tirtax-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    Rincian Biaya
                </h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Ongkos Kirim</span>
                        <span class="font-semibold text-gray-900">Rp
                            {{ number_format($shipment->total_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Asuransi</span>
                        <span class="font-semibold text-gray-900">Rp 0</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Biaya Admin</span>
                        <span class="font-semibold text-gray-900">Rp 0</span>
                    </div>
                    <div class="border-t border-gray-200 pt-3 flex justify-between">
                        <span class="font-bold text-gray-900">Total</span>
                        <span class="font-bold text-lg text-accent-600">Rp
                            {{ number_format($shipment->total_cost, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Status Pembayaran --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6 text-tirtax-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Status Pembayaran
                </h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500">Status</p>
                        @if(in_array($shipment->status, ['paid', 'picked_up', 'in_transit', 'delivered']))
                            <p class="font-semibold text-green-600">✅ Sudah Dibayar</p>
                        @elseif($shipment->status === 'created')
                            <p class="font-semibold text-yellow-600">⏳ Menunggu Pembayaran</p>
                        @elseif($shipment->status === 'cancelled')
                            <p class="font-semibold text-red-600">❌ Dibatalkan</p>
                        @else
                            <p class="font-semibold text-gray-600">-</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-gray-500">Metode</p>
                        <p class="font-semibold text-gray-900">Midtrans Payment Gateway</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Tanggal Bayar</p>
                        @php
                            $paidHistory = $shipment->histories()->where('status', 'paid')->first();
                        @endphp
                        @if($paidHistory)
                            <p class="font-semibold text-gray-900">{{ $paidHistory->created_at->format('d M Y, H:i') }} WIB</p>
                        @else
                            <p class="font-semibold text-gray-400">Belum dibayar</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Timeline Riwayat Pengiriman --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-6">
            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-tirtax-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Riwayat Pengiriman
            </h3>

            @php
                $histories = $shipment->histories()->orderBy('created_at', 'desc')->get();
            @endphp

            @forelse($histories as $index => $history)
                <div class="relative pl-12 pb-8 {{ $index !== $histories->count() - 1 ? 'border-l-2 border-gray-200' : '' }}">
                    {{-- Icon --}}
                    <div class="absolute left-0 top-0 w-8 h-8 rounded-full flex items-center justify-center 
                                    {{ $index === 0 ? 'bg-tirtax-600' : 'bg-gray-300' }}">
                        @if($index === 0)
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        @else
                            <div class="w-3 h-3 bg-white rounded-full"></div>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="bg-gray-50 rounded-lg p-4 ml-4">
                        <div class="flex justify-between items-start mb-2 flex-wrap gap-2">
                            <h4 class="font-bold text-gray-900">{{ $history->description }}</h4>
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
                            <span class="text-xs px-2 py-1 bg-tirtax-100 text-tirtax-700 rounded font-medium">
                                {{ $label }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mb-1">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                            </svg>
                            {{ $history->location }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ $history->created_at->format('d M Y, H:i') }} WIB
                        </p>

                        {{-- Foto POD --}}
                        @if($history->photo_url)
                            <div class="mt-4">
                                <p class="text-sm font-semibold text-gray-700 mb-2">📷 Foto Bukti Pengiriman:</p>
                                <div class="border-2 border-gray-300 rounded-lg overflow-hidden inline-block">
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

        {{-- ========================================== --}}
        {{-- RATING & FEEDBACK SECTION --}}
        {{-- ========================================== --}}

        {{-- Form Rating (Hanya muncul jika delivered & belum dinilai) --}}
        @if($shipment->status === 'delivered' && is_null($shipment->rating))
            <div
                class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-2xl shadow-lg border-2 border-yellow-200 p-6 mb-6">
                <h3 class="text-xl font-bold text-gray-900 mb-2 flex items-center gap-2">
                    <svg class="w-7 h-7 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                        </path>
                    </svg>
                    Beri Penilaian Pengiriman
                </h3>
                <p class="text-gray-600 mb-6 text-sm">Bagaimana pengalaman pengiriman Anda? Beri bintang & feedback untuk
                    meningkatkan layanan kami.</p>

                <form action="{{ route('shipments.review', $shipment->id) }}" method="POST"
                    x-data="{ rating: 0, hoverRating: 0 }">
                    @csrf

                    {{-- Star Rating --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Rating <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer transition-all duration-200"
                                    :class="(hoverRating >= {{ $i }} || rating >= {{ $i }}) ? 'text-yellow-400 text-5xl scale-110' : 'text-gray-300 text-5xl'"
                                    @mouseenter="hoverRating = {{ $i }}" @mouseleave="hoverRating = 0">
                                    <input type="radio" name="rating" value="{{ $i }}" class="hidden" @change="rating = {{ $i }}"
                                        required>
                                    ★
                                </label>
                            @endfor
                            <span class="ml-3 text-sm font-semibold text-gray-700" x-show="rating > 0">
                                (<span x-text="rating"></span>/5)
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1" x-show="rating === 0">* Klik bintang untuk memberi rating</p>
                    </div>

                    {{-- Feedback Textarea --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Feedback <span class="text-gray-400 text-xs font-normal">(Opsional)</span>
                        </label>
                        <textarea name="feedback" rows="3"
                            placeholder="Contoh: Kurir ramah, paket sampai tepat waktu, packaging rapi..." maxlength="500"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500 p-3 border"></textarea>
                        <p class="text-xs text-gray-500 mt-1">Maksimal 500 karakter</p>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white font-semibold rounded-lg hover:from-yellow-600 hover:to-orange-600 transition shadow-md flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Kirim Rating & Feedback
                    </button>
                </form>
            </div>
        @endif

        {{-- Tampilkan Rating Jika Sudah Dinilai --}}
        @if(!is_null($shipment->rating))
            <div
                class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-2xl shadow-lg border-2 border-yellow-200 p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                        </path>
                    </svg>
                    Penilaian Anda
                </h3>
                <div class="flex items-center gap-2 mb-3">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="text-3xl {{ $i <= $shipment->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                    @endfor
                    <span class="ml-2 text-lg font-bold text-gray-700">({{ $shipment->rating }}/5)</span>
                </div>
                @if($shipment->feedback)
                    <div class="bg-white rounded-lg p-4 mt-3 border border-yellow-100">
                        <p class="text-sm text-gray-700 italic">"{{ $shipment->feedback }}"</p>
                    </div>
                @else
                    <p class="text-sm text-gray-500 italic mt-2">Tidak ada feedback tertulis.</p>
                @endif
                <p class="text-xs text-green-600 mt-3 font-semibold">✅ Terima kasih! Penilaian Anda telah tercatat.</p>
            </div>
        @endif

        {{-- Tombol Kembali --}}
        <div class="flex justify-center">
            <a href="{{ route('shipments.my') }}"
                class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                ← Kembali ke Riwayat Pengiriman
            </a>
        </div>
    </div>

    {{-- Modal Cancel Pengiriman --}}
    <div id="cancelModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Batalkan Pesanan?</h3>
                    <p class="text-sm text-gray-500">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                <p class="text-sm text-yellow-800">
                    ⚠️ <strong>Perhatian:</strong> Pesanan hanya dapat dibatalkan sebelum pembayaran dilakukan.
                    Setelah dibatalkan, Anda perlu membuat pesanan baru jika ingin mengirim paket.
                </p>
            </div>

            <form action="{{ route('shipments.cancel', $shipment->id) }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Alasan Pembatalan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="cancel_reason" rows="3" required
                        placeholder="Contoh: Salah input alamat penerima, ingin mengubah tujuan, dll." minlength="10"
                        maxlength="500"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 p-3 border"></textarea>
                    <p class="text-xs text-gray-500 mt-1">Minimal 10 karakter, maksimal 500 karakter</p>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeCancelModal()"
                        class="flex-1 px-4 py-3 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-3 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition shadow-md">
                        Ya, Batalkan Pesanan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openCancelModal() {
            document.getElementById('cancelModal').classList.remove('hidden');
            document.getElementById('cancelModal').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeCancelModal() {
            document.getElementById('cancelModal').classList.add('hidden');
            document.getElementById('cancelModal').classList.remove('flex');
            document.body.style.overflow = '';
        }

        document.getElementById('cancelModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeCancelModal();
            }
        });
    </script>
@endsection