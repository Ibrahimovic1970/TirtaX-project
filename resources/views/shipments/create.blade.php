@extends('layouts.app')

@section('title', 'Kirim Paket - TirtaX')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    
    {{-- Breadcrumb --}}
    <div class="mb-6">
        <a href="{{ route('customer.dashboard') }}" class="text-navy-700 hover:text-navy-900 font-medium flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Dashboard
        </a>
    </div>

    {{-- Header --}}
    <div class="bg-gradient-to-r from-navy-900 to-navy-800 rounded-2xl shadow-xl p-8 mb-8 text-white">
        <h1 class="text-3xl font-bold mb-2">📦 Kirim Paket Baru</h1>
        <p class="text-sky-200">Isi detail pengiriman Anda. Ongkir akan dihitung otomatis.</p>
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

    {{-- Form Kirim Paket --}}
    <div class="bg-white rounded-2xl shadow-lg border border-sky-100 p-8">
        <form action="{{ route('shipments.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Section: Informasi Pengiriman --}}
            <div class="border-b border-sky-100 pb-6 mb-6">
                <h2 class="text-xl font-bold text-navy-950 mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6 text-navy-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Informasi Pengiriman
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Kota Asal --}}
                    <div>
                        <label class="block text-sm font-medium text-navy-900 mb-2">
                            Kota Asal <span class="text-red-500">*</span>
                        </label>
                        <select name="origin_city" id="origin_city" required
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900">
                            <option value="">-- Pilih Kota Asal --</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->city }}" {{ old('origin_city') == $branch->city ? 'selected' : '' }}>
                                    {{ $branch->city }}, {{ $branch->province }}
                                </option>
                            @endforeach
                        </select>
                        @error('origin_city')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kota Tujuan --}}
                    <div>
                        <label class="block text-sm font-medium text-navy-900 mb-2">
                            Kota Tujuan <span class="text-red-500">*</span>
                        </label>
                        <select name="destination_city" id="destination_city" required
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900">
                            <option value="">-- Pilih Kota Tujuan --</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->city }}" {{ old('destination_city') == $branch->city ? 'selected' : '' }}>
                                    {{ $branch->city }}, {{ $branch->province }}
                                </option>
                            @endforeach
                        </select>
                        @error('destination_city')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Jenis Layanan --}}
                    <div>
                        <label class="block text-sm font-medium text-navy-900 mb-2">
                            Jenis Layanan <span class="text-red-500">*</span>
                        </label>
                        <select name="service_type" id="service_type" required
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900">
                            <option value="">-- Pilih Layanan --</option>
                            @foreach($services as $service)
                                <option value="{{ $service }}" {{ old('service_type') == $service ? 'selected' : '' }}>
                                    @if($service == 'REGULER') Reguler (3-5 hari)
                                    @elseif($service == 'EKONOMI') Ekonomi (5-7 hari)
                                    @elseif($service == 'EXPRESS') Express (1-2 hari)
                                    @elseif($service == 'SAMEDAY') Same Day (Hari ini)
                                    @else {{ $service }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('service_type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Berat Paket --}}
                    <div>
                        <label class="block text-sm font-medium text-navy-900 mb-2">
                            Berat Paket (Kg) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="weight" id="weight" value="{{ old('weight') }}" 
                               required min="0.1" step="0.1" placeholder="Contoh: 2.5"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900">
                        @error('weight')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Tombol Hitung Ongkir --}}
                <div class="mt-6">
                    <button type="button" id="btnCalculate" 
                            class="w-full md:w-auto px-8 py-3 bg-sky-600 text-white font-semibold rounded-lg hover:bg-sky-700 transition shadow-md flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        Hitung Estimasi Ongkir
                    </button>
                </div>

                {{-- Hasil Perhitungan Ongkir --}}
                <div id="shippingResult" class="hidden mt-6 bg-sky-50 border-2 border-sky-200 rounded-xl p-6">
                    <h3 class="text-lg font-bold text-navy-950 mb-4"> Estimasi Ongkos Kirim</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                        <div>
                            <p class="text-xs text-gray-500">Kota Asal</p>
                            <p class="font-bold text-navy-900" id="resultOrigin">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Kota Tujuan</p>
                            <p class="font-bold text-navy-900" id="resultDestination">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Berat</p>
                            <p class="font-bold text-navy-900" id="resultWeight">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Estimasi</p>
                            <p class="font-bold text-navy-900" id="resultEstimation">-</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg p-4 border border-sky-200">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm text-gray-600">Total Ongkos Kirim</p>
                                <p class="text-3xl font-bold text-accent-600" id="resultTotalCost">Rp 0</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500">Harga Dasar</p>
                                <p class="font-semibold text-navy-900" id="resultBasePrice">Rp 0</p>
                                <p class="text-xs text-gray-500 mt-2">Harga per Kg</p>
                                <p class="font-semibold text-navy-900" id="resultPricePerKg">Rp 0</p>
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-3">* Harga final akan dikonfirmasi saat checkout</p>
                </div>

                {{-- Error Result --}}
                <div id="shippingError" class="hidden mt-6 bg-red-50 border-2 border-red-200 rounded-xl p-6">
                    <p class="text-red-700 font-medium" id="errorMessage"></p>
                </div>
            </div>

            {{-- Section: Informasi Penerima --}}
            <div>
                <h2 class="text-xl font-bold text-navy-950 mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6 text-navy-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Informasi Penerima
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-navy-900 mb-2">
                            Nama Penerima <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="receiver_name" value="{{ old('receiver_name') }}" required
                               placeholder="Nama lengkap penerima"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900">
                        @error('receiver_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-navy-900 mb-2">
                            No. HP Penerima <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="receiver_phone" value="{{ old('receiver_phone') }}" required
                               placeholder="08xxxxxxxxxx"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900">
                        @error('receiver_phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-navy-900 mb-2">
                        Alamat Lengkap Penerima <span class="text-red-500">*</span>
                    </label>
                    <textarea name="receiver_address" rows="3" required
                              placeholder="Jl. Contoh No. 123, RT/RW, Kelurahan, Kecamatan"
                              class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900">{{ old('receiver_address') }}</textarea>
                    @error('receiver_address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Hidden Input untuk Total Cost --}}
            <input type="hidden" name="total_cost" id="total_cost" value="{{ old('total_cost') }}">

            {{-- Tombol Submit --}}
            <div class="flex gap-3 pt-6 border-t border-sky-100">
                <a href="{{ route('customer.dashboard') }}" 
                   class="flex-1 text-center py-3 border-2 border-gray-300 rounded-xl text-gray-700 font-semibold hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" id="btnSubmit" disabled
                        class="flex-1 py-3 bg-gray-400 text-white font-bold rounded-xl transition shadow-lg flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span id="btnSubmitText">Hitung Ongkir Dulu</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- JavaScript untuk AJAX Calculate Shipping --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnCalculate = document.getElementById('btnCalculate');
    const btnSubmit = document.getElementById('btnSubmit');
    const btnSubmitText = document.getElementById('btnSubmitText');
    const shippingResult = document.getElementById('shippingResult');
    const shippingError = document.getElementById('shippingError');
    const totalCostInput = document.getElementById('total_cost');

    btnCalculate.addEventListener('click', function() {
        const origin = document.getElementById('origin_city').value;
        const destination = document.getElementById('destination_city').value;
        const serviceType = document.getElementById('service_type').value;
        const weight = document.getElementById('weight').value;

        // Validasi input
        if (!origin || !destination || !serviceType || !weight) {
            shippingError.classList.remove('hidden');
            shippingResult.classList.add('hidden');
            document.getElementById('errorMessage').textContent = 'Mohon lengkapi semua field terlebih dahulu!';
            return;
        }

        // Loading state
        btnCalculate.disabled = true;
        btnCalculate.innerHTML = '<svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Menghitung...';

        // AJAX Request
        fetch('/api/calculate-shipping', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                origin: origin,
                destination: destination,
                weight: parseFloat(weight),
                service_type: serviceType
            })
        })
        .then(response => response.json())
        .then(data => {
            btnCalculate.disabled = false;
            btnCalculate.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg> Hitung Estimasi Ongkir';

            if (data.success) {
                // Tampilkan hasil
                shippingError.classList.add('hidden');
                shippingResult.classList.remove('hidden');

                document.getElementById('resultOrigin').textContent = data.data.origin;
                document.getElementById('resultDestination').textContent = data.data.destination;
                document.getElementById('resultWeight').textContent = data.data.weight + ' Kg';
                document.getElementById('resultEstimation').textContent = data.data.estimation_days + ' hari';
                document.getElementById('resultTotalCost').textContent = 'Rp ' + parseInt(data.data.total_cost).toLocaleString('id-ID');
                document.getElementById('resultBasePrice').textContent = 'Rp ' + parseInt(data.data.base_price).toLocaleString('id-ID');
                document.getElementById('resultPricePerKg').textContent = 'Rp ' + parseInt(data.data.price_per_kg).toLocaleString('id-ID');

                // Set hidden input
                totalCostInput.value = data.data.total_cost;

                // Enable submit button
                btnSubmit.disabled = false;
                btnSubmit.classList.remove('bg-gray-400');
                btnSubmit.classList.add('bg-navy-800', 'hover:bg-navy-900');
                btnSubmitText.textContent = 'Buat Resi & Lanjut ke Pembayaran';
            } else {
                // Tampilkan error
                shippingResult.classList.add('hidden');
                shippingError.classList.remove('hidden');
                document.getElementById('errorMessage').textContent = data.message || 'Tarif tidak ditemukan untuk rute ini.';
                
                // Disable submit
                btnSubmit.disabled = true;
                btnSubmit.classList.add('bg-gray-400');
                btnSubmit.classList.remove('bg-navy-800', 'hover:bg-navy-900');
                btnSubmitText.textContent = 'Hitung Ongkir Dulu';
            }
        })
        .catch(error => {
            btnCalculate.disabled = false;
            btnCalculate.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg> Hitung Estimasi Ongkir';
            
            shippingResult.classList.add('hidden');
            shippingError.classList.remove('hidden');
            document.getElementById('errorMessage').textContent = 'Gagal menghitung ongkir. Silakan coba lagi.';
            
            btnSubmit.disabled = true;
        });
    });
});
</script>
@endsection