@extends('layouts.app')

@section('title', 'Kelola Pengiriman - TirtaX')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-navy-900 to-navy-800 rounded-2xl shadow-xl p-8 mb-8 text-white">
            <div class="flex justify-between items-start flex-wrap gap-4">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Daftar Semua Pengiriman</h1>
                    <p class="text-sky-200">Kelola dan monitor semua pengiriman di sistem.</p>
                </div>
                <a href="{{ route('admin.dashboard') }}"
                    class="px-6 py-3 bg-sky-300 text-navy-950 rounded-lg font-semibold hover:bg-sky-200 transition shadow-md flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Dashboard
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

        {{-- Filter & Search --}}
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border border-sky-100">
            <form method="GET" action="{{ route('admin.shipments') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-navy-900 mb-2">Cari No. Resi</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Contoh: TRX-20260610-XXXXXX"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900">
                </div>
                <div>
                    <label class="block text-sm font-medium text-navy-900 mb-2">Filter Status</label>
                    <select name="status"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900">
                        <option value="">Semua Status</option>
                        <option value="created" {{ request('status') == 'created' ? 'selected' : '' }}>Menunggu Pembayaran
                        </option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Sudah Dibayar</option>
                        <option value="picked_up" {{ request('status') == 'picked_up' ? 'selected' : '' }}>Diambil Kurir
                        </option>
                        <option value="in_transit" {{ request('status') == 'in_transit' ? 'selected' : '' }}>Dalam Perjalanan
                        </option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit"
                        class="w-full px-6 py-3 bg-navy-800 text-white rounded-lg hover:bg-navy-900 transition font-semibold flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Filter
                    </button>
                </div>
            </form>
        </div>

        {{-- Tabel Pengiriman --}}
        <div class="bg-white rounded-2xl shadow-lg border border-sky-100 overflow-hidden">
            @if($shipments->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-20 h-20 bg-sky-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-navy-950 mb-2">Tidak ada pengiriman</h3>
                    <p class="text-gray-500">Belum ada pengiriman di sistem.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-sky-100">
                        <thead class="bg-navy-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">No. Resi</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">Tujuan</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">Biaya</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">Tanggal</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-navy-700 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-sky-100">
                            @foreach($shipments as $shipment)
                                <tr class="hover:bg-sky-50 transition">
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-bold text-navy-700">{{ $shipment->tracking_number }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-navy-950">{{ $shipment->user->name ?? '-' }}</div>
                                        <div class="text-xs text-gray-500">{{ $shipment->user->email ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $shipment->destination_city }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-navy-950">Rp
                                        {{ number_format($shipment->total_cost, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusConfig = [
                                                'created' => ['bg-sky-100', 'text-navy-800', 'Menunggu Bayar'],
                                                'paid' => ['bg-navy-100', 'text-navy-900', 'Sudah Dibayar'],
                                                'picked_up' => ['bg-blue-100', 'text-blue-800', 'Diambil Kurir'],
                                                'in_transit' => ['bg-indigo-100', 'text-indigo-800', 'Dalam Perjalanan'],
                                                'delivered' => ['bg-green-100', 'text-green-800', 'Selesai'],
                                                'cancelled' => ['bg-red-100', 'text-red-800', 'Dibatalkan'],
                                            ];
                                            $config = $statusConfig[$shipment->status] ?? $statusConfig['created'];
                                        @endphp
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $config[0] }} {{ $config[1] }}">
                                            {{ $config[2] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $shipment->created_at->format('d M Y') }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <button type="button"
                                            onclick="openModal({{ $shipment->id }}, '{{ $shipment->tracking_number }}')"
                                            class="px-3 py-1.5 bg-navy-800 text-white rounded-lg text-xs font-semibold hover:bg-navy-900 transition">
                                            Update Status
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-sky-100">
                    {{ $shipments->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Modal Update Status --}}
    <div id="updateModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-navy-950">Update Status Pengiriman</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <form id="updateStatusForm" method="POST">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-navy-900 mb-2">Status Baru</label>
                        <select name="status" required
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900">
                            <option value="created">Menunggu Pembayaran</option>
                            <option value="paid">Sudah Dibayar</option>
                            <option value="picked_up">Diambil Kurir</option>
                            <option value="in_transit">Dalam Perjalanan</option>
                            <option value="delivered">Selesai</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-navy-900 mb-2">Deskripsi</label>
                        <textarea name="description" rows="3" placeholder="Contoh: Paket sudah sampai"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-navy-900 mb-2">Lokasi (Opsional)</label>
                        <input type="text" name="location" placeholder="Contoh: Jakarta Selatan"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900">
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="button" onclick="closeModal()"
                            class="flex-1 px-4 py-2 border-2 border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-navy-800 text-white rounded-lg font-semibold hover:bg-navy-900 transition shadow-lg">
                            Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Fungsi untuk membuka modal
        function openModal(shipmentId, trackingNumber) {
            const modal = document.getElementById('updateModal');
            const form = document.getElementById('updateStatusForm');

            // Set action form dengan ID pengiriman
            form.action = '/admin/shipments/' + shipmentId + '/status';

            // Tampilkan modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        // Fungsi untuk menutup modal
        function closeModal() {
            const modal = document.getElementById('updateModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Tutup modal jika klik di luar
        document.getElementById('updateModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
@endsection