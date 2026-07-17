@extends('layouts.app')

@section('title', 'Pembayaran Berhasil - TirtaX')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-12 bg-gradient-to-br from-navy-50 to-sky-50">
    <div class="max-w-2xl w-full">
        <div class="bg-white rounded-2xl shadow-xl border border-sky-100 p-8">

            {{-- Success Icon --}}
            <div class="text-center mb-8">
                <div class="w-24 h-24 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-navy-950 mb-2">Pembayaran Berhasil!</h1>
                <p class="text-gray-600">Terima kasih, pembayaran Anda telah berhasil diproses</p>
            </div>

            {{-- Shipment Info --}}
            <div class="bg-navy-50 rounded-xl p-6 mb-6 border-2 border-navy-200">
                <h2 class="text-lg font-bold text-navy-950 mb-4">📦 Detail Pengiriman</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">No. Resi</span>
                        <span class="font-bold text-navy-900">{{ $shipment->tracking_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Tujuan</span>
                        <span class="font-semibold text-navy-900">{{ $shipment->destination_city }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Total Pembayaran</span>
                        <span class="text-xl font-bold text-green-600">Rp {{ number_format($shipment->total_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Status</span>
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">
                            Sudah Dibayar
                        </span>
                    </div>
                </div>
            </div>

            {{-- Next Steps --}}
            <div class="bg-sky-50 rounded-xl p-6 mb-6 border border-sky-200">
                <h3 class="font-bold text-navy-950 mb-4">📋 Langkah Selanjutnya</h3>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3">
                        <div class="w-6 h-6 bg-sky-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <span class="text-white text-xs font-bold">1</span>
                        </div>
                        <div>
                            <p class="font-semibold text-navy-950">Pembayaran Dikonfirmasi</p>
                            <p class="text-sm text-gray-600">Pembayaran Anda telah dikonfirmasi</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="w-6 h-6 bg-sky-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <span class="text-white text-xs font-bold">2</span>
                        </div>
                        <div>
                            <p class="font-semibold text-navy-950">Paket Diproses</p>
                            <p class="text-sm text-gray-600">Paket akan segera diproses dan dikirim</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="w-6 h-6 bg-sky-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <span class="text-white text-xs font-bold">3</span>
                        </div>
                        <div>
                            <p class="font-semibold text-navy-950">Lacak Paket</p>
                            <p class="text-sm text-gray-600">Pantau perjalanan paket Anda secara real-time</p>
                        </div>
                    </li>
                </ul>
            </div>

            {{-- Action Buttons --}}
            <div class="space-y-3">
                <a href="{{ route('tracking.show', $shipment->tracking_number) }}"
                   class="block w-full py-3 bg-gradient-to-r from-navy-800 to-navy-900 text-white font-bold rounded-lg hover:from-navy-900 hover:to-navy-950 transition shadow-lg text-center">
                    📍 Lacak Paket Sekarang
                </a>
                <a href="{{ route('shipments.my') }}"
                   class="block w-full py-3 border-2 border-gray-300 text-gray-700 font-bold rounded-lg hover:bg-gray-50 transition text-center">
                    ← Kembali ke Riwayat Pengiriman
                </a>
            </div>

            {{-- Info Card --}}
            <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-yellow-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-yellow-800">Simpan No. Resi Ini</p>
                        <p class="text-xs text-yellow-700 mt-1">
                            Jangan lupa simpan nomor resi <strong>{{ $shipment->tracking_number }}</strong> untuk melacak paket Anda
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Copy Button --}}
        <div class="mt-6 text-center">
            <button onclick="copyToClipboard('{{ $shipment->tracking_number }}')"
                    class="px-4 py-2 bg-white border-2 border-sky-300 text-sky-700 rounded-lg hover:bg-sky-50 transition font-semibold text-sm">
                📋 Copy No. Resi
            </button>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('No. Resi berhasil disalin: ' + text);
    }, function(err) {
        console.error('Gagal menyalin: ', err);
    });
}
</script>
@endsection
