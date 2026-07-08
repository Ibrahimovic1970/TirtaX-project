@extends('layouts.app')

@section('title', 'Edit Tarif - TirtaX')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    
    {{-- Breadcrumb --}}
    <div class="mb-6">
        <a href="{{ route('admin.rates.index') }}" class="text-navy-700 hover:text-navy-900 font-medium flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Daftar Tarif
        </a>
    </div>

    {{-- Header --}}
    <div class="bg-gradient-to-r from-navy-900 to-navy-800 rounded-2xl shadow-xl p-8 mb-8 text-white">
        <h1 class="text-3xl font-bold mb-2">✏️ Edit Tarif</h1>
        <p class="text-sky-200">Ubah tarif pengiriman untuk rute ini.</p>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-2xl shadow-lg border border-sky-100 p-8">
        <form action="{{ route('admin.rates.update', $rate->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Kota Asal --}}
            <div>
                <label class="block text-sm font-medium text-navy-900 mb-2">
                    Kota Asal <span class="text-red-500">*</span>
                </label>
                <input type="text" name="origin_city" value="{{ old('origin_city', $rate->origin_city) }}" required
                       placeholder="Contoh: Jakarta"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900 placeholder-gray-400">
                @error('origin_city')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Kota Tujuan --}}
            <div>
                <label class="block text-sm font-medium text-navy-900 mb-2">
                    Kota Tujuan <span class="text-red-500">*</span>
                </label>
                <input type="text" name="destination_city" value="{{ old('destination_city', $rate->destination_city) }}" required
                       placeholder="Contoh: Bandung"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900 placeholder-gray-400">
                @error('destination_city')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Jenis Layanan --}}
            <div>
                <label class="block text-sm font-medium text-navy-900 mb-2">
                    Jenis Layanan <span class="text-red-500">*</span>
                </label>
                <select name="service_type" required class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900">
                    <option value="">Pilih Layanan</option>
                    <option value="REGULER" {{ old('service_type', $rate->service_type) == 'REGULER' ? 'selected' : '' }}>Reguler (3-5 hari)</option>
                    <option value="EKONOMI" {{ old('service_type', $rate->service_type) == 'EKONOMI' ? 'selected' : '' }}>Ekonomi (5-7 hari)</option>
                    <option value="EXPRESS" {{ old('service_type', $rate->service_type) == 'EXPRESS' ? 'selected' : '' }}>Express (1-2 hari)</option>
                    <option value="SAMEDAY" {{ old('service_type', $rate->service_type) == 'SAMEDAY' ? 'selected' : '' }}>Same Day (Hari ini)</option>
                </select>
                @error('service_type')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Harga Dasar --}}
            <div>
                <label class="block text-sm font-medium text-navy-900 mb-2">
                    Harga Dasar (Rp) <span class="text-red-500">*</span>
                </label>
                <input type="number" name="base_price" value="{{ old('base_price', $rate->base_price) }}" required min="0" step="1000"
                       placeholder="Contoh: 5000"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900 placeholder-gray-400">
                <p class="text-xs text-gray-500 mt-1">Harga dasar untuk paket pertama</p>
                @error('base_price')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Harga per Kg --}}
            <div>
                <label class="block text-sm font-medium text-navy-900 mb-2">
                    Harga per Kg (Rp) <span class="text-red-500">*</span>
                </label>
                <input type="number" name="price_per_kg" value="{{ old('price_per_kg', $rate->price_per_kg) }}" required min="0" step="1000"
                       placeholder="Contoh: 10000"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900 placeholder-gray-400">
                <p class="text-xs text-gray-500 mt-1">Harga tambahan per kilogram</p>
                @error('price_per_kg')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Estimasi Hari --}}
            <div>
                <label class="block text-sm font-medium text-navy-900 mb-2">
                    Estimasi Pengiriman (Hari) <span class="text-red-500">*</span>
                </label>
                <input type="number" name="estimation_days" value="{{ old('estimation_days', $rate->estimation_days) }}" required min="1" max="30"
                       placeholder="Contoh: 3"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900 placeholder-gray-400">
                @error('estimation_days')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Status Aktif --}}
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1" 
                       {{ old('is_active', $rate->is_active) ? 'checked' : '' }}
                       class="h-5 w-5 text-navy-700 focus:ring-navy-700 border-gray-300 rounded">
                <label for="is_active" class="text-sm font-medium text-navy-900">
                    Tarif Aktif (dapat digunakan customer)
                </label>
            </div>

            {{-- Tombol Submit --}}
            <div class="flex gap-3 pt-4">
                <a href="{{ route('admin.rates.index') }}" 
                   class="flex-1 text-center py-3 border-2 border-gray-300 rounded-xl text-gray-700 font-semibold hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" 
                        class="flex-1 py-3 bg-navy-800 text-white font-bold rounded-xl hover:bg-navy-900 transition shadow-lg">
                    💾 Update Tarif
                </button>
            </div>
        </form>
    </div>
</div>
@endsection