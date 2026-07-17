@extends('layouts.app')

@section('title', 'Pembayaran - TirtaX')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-12 bg-gradient-to-br from-navy-50 to-sky-50">
    <div class="max-w-2xl w-full">
        <div class="bg-white rounded-2xl shadow-xl border border-sky-100 p-8">

            {{-- Header --}}
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-gradient-to-br from-navy-800 to-navy-900 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <svg class="w-8 h-8 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-navy-950 mb-2">Pembayaran Pengiriman</h1>
                <p class="text-gray-600">Selesaikan pembayaran untuk pengiriman Anda</p>
            </div>

            {{-- Shipment Info --}}
            <div class="bg-navy-50 rounded-xl p-6 mb-6 border-2 border-navy-200">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">No. Resi</p>
                        <p class="text-lg font-bold text-navy-900">{{ $shipment->tracking_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Biaya</p>
                        <p class="text-2xl font-bold text-accent-600">Rp {{ number_format($shipment->total_cost, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Tujuan</p>
                        <p class="font-semibold text-navy-900">{{ $shipment->destination_city }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Berat</p>
                        <p class="font-semibold text-navy-900">{{ $shipment->weight }} Kg</p>
                    </div>
                </div>
            </div>

            {{-- Payment Button --}}
            <div class="text-center mb-6">
                <button id="pay-button"
                        class="w-full py-4 bg-gradient-to-r from-navy-800 to-navy-900 text-white font-bold rounded-lg hover:from-navy-900 hover:to-navy-950 transition shadow-lg text-lg">
                    Bayar Sekarang
                </button>
                <p class="text-xs text-gray-500 mt-2">
                    Order ID: {{ $uniqueOrderId ?? $shipment->tracking_number }}
                </p>
            </div>

            {{-- Payment Info --}}
            <div class="bg-sky-50 rounded-xl p-6 border border-sky-200">
                <h3 class="font-bold text-navy-950 mb-3">💡 Informasi Pembayaran</h3>
                <ul class="space-y-2 text-sm text-gray-700">
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-sky-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Klik tombol "Bayar Sekarang" untuk melanjutkan</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-sky-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Pilih metode pembayaran yang tersedia (Transfer Bank, E-Wallet, dll)</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-sky-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Selesaikan pembayaran sesuai instruksi</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-sky-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Paket akan diproses setelah pembayaran dikonfirmasi</span>
                    </li>
                </ul>
            </div>

            {{-- Back Button --}}
            <div class="text-center mt-6">
                <a href="{{ route('shipments.my') }}" class="text-navy-700 hover:text-navy-900 font-semibold">
                    ← Kembali ke Riwayat Pengiriman
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Midtrans Snap JS --}}
@if(config('midtrans.is_production'))
    <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@else
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@endif

<script>
document.getElementById('pay-button').addEventListener('click', function(e) {
    e.preventDefault();

    snap.pay('{{ $snapToken }}', {
        onSuccess: function(result) {
            // Redirect to success page
            window.location.href = '{{ route("payment.success", $shipment->id) }}';
        },
        onPending: function(result) {
            // Save pending transaction
            window.location.href = '{{ route("shipments.my") }}';
        },
        onError: function(result) {
            alert('Pembayaran gagal. Silakan coba lagi.');
            console.error('Payment error:', result);
        },
        onClose: function() {
            // User closed the popup
            alert('Anda menutup popup pembayaran tanpa menyelesaikan transaksi.');
        }
    });
});
</script>
@endsection
