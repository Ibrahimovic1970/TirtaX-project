@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto px-4 py-12" x-data="{ showError: {{ $error ? 'true' : 'false' }} }">
        <!-- Error Alert -->
        @if($error ?? false)
            <div class="bg-red-50 border-l-4 border-red-500 rounded-r-xl p-5 mb-6 shadow-md" x-show="showError" x-transition>
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-red-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="flex-1">
                        <p class="font-semibold text-red-800">Pembayaran Dibatalkan</p>
                        <p class="text-red-700 text-sm mt-1">{{ $error }}</p>
                    </div>
                    <button @click="showError = false" class="text-red-400 hover:text-red-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-xl p-8 text-center border border-gray-100">
            <div class="w-16 h-16 bg-tirtax-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-tirtax-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
            </div>

            <h2 class="text-2xl font-bold text-gray-900 mb-2">Pembayaran Pengiriman</h2>
            <p class="text-gray-500 mb-6">No. Resi: <span
                    class="font-semibold text-tirtax-600">{{ $shipment->tracking_number }}</span></p>

            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 mb-6 border border-gray-200">
                <p class="text-sm text-gray-600 mb-1">Total Pembayaran</p>
                <p class="text-3xl font-bold text-accent-600">Rp {{ number_format($shipment->total_cost, 0, ',', '.') }}</p>
            </div>

            <button id="pay-button"
                class="w-full bg-gradient-to-r from-accent-500 to-orange-600 text-white font-bold py-4 rounded-xl hover:from-orange-600 hover:to-orange-700 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-lg flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                    </path>
                </svg>
                Bayar Sekarang
            </button>

            <a href="{{ route('shipments.my') }}"
                class="inline-block mt-6 text-gray-500 hover:text-gray-700 font-medium transition">
                ← Kembali ke Riwayat Pengiriman
            </a>
        </div>
    </div>

    @if(config('services.midtrans.is_production'))
        <script src="https://app.midtrans.com/snap/snap.js"
            data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    @else
        <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    @endif

    <script>
        let isProcessing = false;

        document.getElementById('pay-button').addEventListener('click', function () {
            if (isProcessing) return;

            isProcessing = true;
            const btn = this;
            const originalContent = btn.innerHTML;

            btn.innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...`;
            btn.disabled = true;

            snap.pay('{{ $snapToken }}', {
                onSuccess: function (result) {
                    window.location.href = '{{ route("payment.success", $shipment->id) }}';
                },
                onPending: function (result) {
                    window.location.href = '{{ route("shipments.my") }}?payment=pending';
                },
                onError: function (result) {
                    window.location.href = '{{ route("payment.show", $shipment->id) }}?error=1';
                },
                onClose: function () {
                    window.location.href = '{{ route("payment.show", $shipment->id) }}?error=1';
                }
            });
        });
    </script>
@endsection