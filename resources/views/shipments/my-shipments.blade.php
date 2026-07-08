@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-12">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-navy-950 mb-2">Riwayat Pengiriman Saya</h1>
                <p class="text-gray-600">Kelola dan lacak semua paket Anda dalam satu tempat.</p>
            </div>
            <a href="{{ route('shipments.create') }}"
                class="inline-flex items-center gap-2 bg-navy-800 text-white px-6 py-3 rounded-xl hover:bg-navy-900 transition font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Kirim Paket Baru
            </a>
        </div>

        {{-- Success Alert --}}
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 rounded-r-xl p-5 mb-6 shadow-md">
                <div class="flex items-start">
                    <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-green-900">Berhasil!</p>
                        <p class="text-green-700 text-sm mt-1">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Error Alert --}}
        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 rounded-r-xl p-5 mb-6 shadow-md">
                <div class="flex items-start">
                    <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-red-900">Perhatian!</p>
                        <p class="text-red-700 text-sm mt-1">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Empty State --}}
        @if($shipments->isEmpty())
            <div class="bg-white rounded-2xl shadow-lg p-16 text-center border border-sky-100">
                <div class="w-24 h-24 bg-sky-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-navy-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-navy-950 mb-2">Belum ada pengiriman</h3>
                <p class="text-gray-500 mb-6 max-w-md mx-auto">Mulai kirim paket pertama Anda dan nikmati kemudahan layanan
                    ekspedisi TirtaX.</p>
                <a href="{{ route('shipments.create') }}"
                    class="inline-flex items-center gap-2 bg-navy-800 text-white px-8 py-3 rounded-xl hover:bg-navy-900 transition font-semibold shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Kirim Paket Pertama
                </a>
            </div>
        @else
            {{-- Shipments Table --}}
            <div class="bg-white rounded-2xl shadow-lg border border-sky-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-sky-100">
                        <thead class="bg-navy-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-navy-700 uppercase tracking-wider">No.
                                    Resi</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-navy-700 uppercase tracking-wider">Tujuan
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-navy-700 uppercase tracking-wider">
                                    Penerima</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-navy-700 uppercase tracking-wider">Biaya
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-navy-700 uppercase tracking-wider">Status
                                </th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-navy-700 uppercase tracking-wider">Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-sky-100">
                            @foreach($shipments as $shipment)
                                @php
                                    $statusConfig = [
                                        'created' => ['bg-sky-100', 'text-navy-800', 'border-sky-300', 'Menunggu Pembayaran', '⏳'],
                                        'paid' => ['bg-navy-100', 'text-navy-900', 'border-navy-300', 'Sudah Dibayar', '✓'],
                                        'picked_up' => ['bg-blue-100', 'text-blue-800', 'border-blue-300', 'Diambil Kurir', '📦'],
                                        'in_transit' => ['bg-indigo-100', 'text-indigo-800', 'border-indigo-300', 'Dalam Perjalanan', '🚚'],
                                        'delivered' => ['bg-green-100', 'text-green-800', 'border-green-300', 'Terkirim', '✅'],
                                        'cancelled' => ['bg-red-100', 'text-red-800', 'border-red-300', 'Dibatalkan', '✕'],
                                    ];
                                    $config = $statusConfig[$shipment->status] ?? $statusConfig['created'];
                                @endphp
                                <tr class="hover:bg-sky-50 transition duration-200">
                                    {{-- No. Resi --}}
                                    <td class="px-6 py-5">
                                        <div class="text-sm font-bold text-navy-700">{{ $shipment->tracking_number }}</div>
                                        <div class="text-xs text-gray-400 mt-1">{{ $shipment->created_at->format('d M Y, H:i') }}
                                            WIB</div>
                                    </td>

                                    {{-- Tujuan --}}
                                    <td class="px-6 py-5">
                                        <div class="text-sm font-semibold text-navy-950">{{ $shipment->destination_city }}</div>
                                        <div class="text-xs text-gray-500 mt-1">Dari: {{ $shipment->origin_city }}</div>
                                    </td>

                                    {{-- Penerima --}}
                                    <td class="px-6 py-5">
                                        <div class="text-sm font-medium text-navy-950">{{ $shipment->receiver_name }}</div>
                                        <div class="text-xs text-gray-500 mt-1">{{ $shipment->receiver_phone }}</div>
                                    </td>

                                    {{-- Biaya --}}
                                    <td class="px-6 py-5">
                                        <div class="text-sm font-bold text-navy-950">Rp
                                            {{ number_format($shipment->total_cost, 0, ',', '.') }}</div>
                                        <div class="text-xs text-gray-500 mt-1">{{ $shipment->weight }} Kg</div>
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-6 py-5">
                                        <span
                                            class="px-3 py-1.5 inline-flex items-center gap-1.5 text-xs font-bold rounded-full border {{ $config[0] }} {{ $config[1] }} {{ $config[2] }}">
                                            <span>{{ $config[4] }}</span>
                                            <span>{{ $config[3] }}</span>
                                        </span>
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="px-6 py-5 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('shipments.detail', $shipment->id) }}"
                                                class="inline-flex items-center gap-1 px-4 py-2 text-sm font-semibold text-navy-700 bg-sky-100 rounded-lg hover:bg-sky-200 transition border border-sky-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Detail
                                            </a>
                                            <a href="{{ route('tracking.show', $shipment->tracking_number) }}"
                                                class="inline-flex items-center gap-1 px-4 py-2 text-sm font-semibold text-navy-700 bg-navy-100 rounded-lg hover:bg-navy-200 transition border border-navy-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                                    </path>
                                                </svg>
                                                Lacak
                                            </a>
                                            @if($shipment->status == 'created')
                                                <a href="{{ route('payment.show', $shipment->id) }}"
                                                    class="inline-flex items-center gap-1 px-4 py-2 text-sm font-bold text-white bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg hover:from-orange-600 hover:to-orange-700 transition shadow-md">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                                        </path>
                                                    </svg>
                                                    Bayar
                                                </a>
                                                <button onclick="confirmCancel({{ $shipment->id }}, '{{ $shipment->tracking_number }}')"
                                                    class="inline-flex items-center gap-1 px-4 py-2 text-sm font-bold text-red-700 bg-red-100 rounded-lg hover:bg-red-200 transition border border-red-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                    Batal
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    {{-- Modal Cancel dari Riwayat --}}
    <div id="cancelModalRiwayat"
        class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 hidden items-center justify-center p-4">
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
                    <h3 class="text-xl font-bold text-navy-950">Batalkan Pesanan?</h3>
                    <p class="text-sm text-gray-500">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                <p class="text-sm text-yellow-800">
                    ⚠️ Pesanan hanya dapat dibatalkan sebelum pembayaran dilakukan.
                </p>
            </div>

            <form id="cancelFormRiwayat" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Alasan Pembatalan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="cancel_reason" rows="3" required placeholder="Contoh: Salah input alamat penerima"
                        minlength="10" maxlength="500"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 p-3 border"></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeCancelModalRiwayat()"
                        class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-3 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition shadow-md">
                        Ya, Batalkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function confirmCancel(id, trackingNumber) {
            if (confirm('Apakah Anda yakin ingin membatalkan pesanan ' + trackingNumber + '?')) {
                document.getElementById('cancelFormRiwayat').action = '/my-shipments/' + id + '/cancel';
                document.getElementById('cancelModalRiwayat').classList.remove('hidden');
                document.getElementById('cancelModalRiwayat').classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeCancelModalRiwayat() {
            document.getElementById('cancelModalRiwayat').classList.add('hidden');
            document.getElementById('cancelModalRiwayat').classList.remove('flex');
            document.body.style.overflow = '';
        }

        document.getElementById('cancelModalRiwayat').addEventListener('click', function (e) {
            if (e.target === this) {
                closeCancelModalRiwayat();
            }
        });
    </script>
@endsection