@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto px-4 py-8">

        {{-- Breadcrumb --}}
        <div class="mb-6">
            <a href="{{ route('courier.dashboard') }}"
                class="text-tirtax-600 hover:text-tirtax-700 font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Kembali ke Daftar Antar
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

        {{-- Info Paket Customer --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Nomor Resi</p>
                    <h2 class="text-2xl font-bold text-tirtax-600">{{ $shipment->tracking_number }}</h2>
                </div>
                @php
                    $statusLabels = [
                        'paid' => 'SUDAH DIBAYAR',
                        'picked_up' => 'SUDAH DIAMBIL',
                        'in_transit' => 'DALAM PERJALANAN',
                    ];
                    $statusColors = [
                        'paid' => 'bg-yellow-100 text-yellow-800',
                        'picked_up' => 'bg-purple-100 text-purple-800',
                        'in_transit' => 'bg-indigo-100 text-indigo-800',
                    ];
                    $label = $statusLabels[$shipment->status] ?? 'UNKNOWN';
                    $color = $statusColors[$shipment->status] ?? 'bg-gray-100 text-gray-800';
                @endphp
                <span class="px-4 py-2 text-sm font-bold rounded-full {{ $color }}">
                    {{ $label }}
                </span>
            </div>

            <div class="bg-gray-50 rounded-xl p-4 space-y-3 text-sm border border-gray-200">
                <div class="flex items-start gap-3">
                    <span class="font-semibold text-gray-700 w-32 flex-shrink-0">Penerima:</span>
                    <span class="text-gray-900">{{ $shipment->receiver_name }}</span>
                </div>
                <div class="flex items-start gap-3">
                    <span class="font-semibold text-gray-700 w-32 flex-shrink-0">No. HP:</span>
                    <a href="tel:{{ $shipment->receiver_phone }}" class="text-tirtax-600 hover:text-tirtax-700 font-medium">
                        {{ $shipment->receiver_phone }}
                    </a>
                </div>
                <div class="flex items-start gap-3">
                    <span class="font-semibold text-gray-700 w-32 flex-shrink-0">Alamat:</span>
                    <span class="text-gray-900">{{ $shipment->receiver_address }}</span>
                </div>
                <div class="flex items-start gap-3">
                    <span class="font-semibold text-gray-700 w-32 flex-shrink-0">Kota Tujuan:</span>
                    <span class="text-gray-900">{{ $shipment->destination_city }}</span>
                </div>
                <div class="flex items-start gap-3">
                    <span class="font-semibold text-gray-700 w-32 flex-shrink-0">Berat:</span>
                    <span class="text-gray-900">{{ $shipment->weight }} Kg</span>
                </div>
            </div>
        </div>

        {{-- Form Update Status --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-2">Update Status Pengiriman</h3>
            <p class="text-sm text-gray-500 mb-6">Pilih status baru dan isi keterangan kegiatan Anda.</p>

            <form action="{{ route('courier.shipment.update', $shipment->id) }}" method="POST" enctype="multipart/form-data"
                class="space-y-4">
                @csrf

                {{-- Pilih Status Baru --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Baru</label>
                    <select name="status" required
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-tirtax-500 focus:border-tirtax-500 p-3 border bg-white">
                        @if($shipment->status === 'paid')
                            <option value="picked_up">📦 Paket Diambil (Picked Up)</option>
                        @elseif($shipment->status === 'picked_up')
                            <option value="in_transit">🚚 Dalam Perjalanan (In Transit)</option>
                        @elseif($shipment->status === 'in_transit')
                            <option value="delivered">✅ Paket Diterima (Delivered)</option>
                        @endif
                    </select>
                    <p class="text-xs text-gray-500 mt-1">* Hanya status yang valid sesuai alur yang ditampilkan</p>
                </div>

                {{-- Deskripsi Kegiatan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi Kegiatan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" rows="3" required
                        placeholder="Contoh: Paket sudah diambil dari gudang Jakarta"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-tirtax-500 focus:border-tirtax-500 p-3 border"></textarea>
                </div>

                {{-- Lokasi Saat Ini --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Lokasi Saat Ini
                    </label>
                    <input type="text" name="location" placeholder="Contoh: Hub Jakarta Selatan"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-tirtax-500 focus:border-tirtax-500 p-3 border">
                </div>

                {{-- Upload Foto Bukti Pengiriman --}}
                <div class="bg-orange-50 border-2 border-orange-200 rounded-xl p-5">
                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                        📷 Foto Bukti Pengiriman (Proof of Delivery)
                        <span class="text-red-500">*</span>
                    </label>
                    <p class="text-xs text-orange-700 mb-3">
                        ⚠️ <strong>WAJIB upload foto</strong> saat status <strong>"Paket Diterima (Delivered)"</strong>.
                        Foto ini akan ditampilkan ke customer sebagai bukti paket sudah sampai.
                    </p>
                    <input type="file" name="photo" accept="image/jpeg,image/png,image/jpg"
                        class="w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-tirtax-50 file:text-tirtax-700 hover:file:bg-tirtax-100 border border-gray-300 rounded-lg cursor-pointer">
                    <p class="text-xs text-gray-500 mt-2">* Format: JPG/PNG, Maksimal 2MB</p>
                </div>

                {{-- Tombol Submit --}}
                <div class="flex gap-3 pt-2">
                    <a href="{{ route('courier.dashboard') }}"
                        class="flex-1 text-center py-3 border-2 border-gray-300 rounded-xl text-gray-700 font-semibold hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="flex-1 py-3 bg-navy-800 text-white font-bold rounded-xl hover:bg-navy-900 transition shadow-lg">
                        💾 Simpan Perubahan Status
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection