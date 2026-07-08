@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Operasional Pengiriman</h1>
            <a href="{{ route('shipments.create') }}"
                class="bg-tirtax-600 text-white px-4 py-2 rounded-lg hover:bg-tirtax-700 transition text-sm font-semibold">
                + Buat Pengiriman Baru
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Resi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengirim
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tujuan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($shipments as $shipment)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-tirtax-600">
                                {{ $shipment->tracking_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $shipment->user->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $shipment->destination_city }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $shipment->status == 'delivered' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ strtoupper(str_replace('_', ' ', $shipment->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <!-- Tombol Trigger Modal AlpineJS -->
                                <button x-data
                                    @click="$dispatch('open-modal', { id: {{ $shipment->id }}, currentStatus: '{{ $shipment->status }}' })"
                                    class="text-tirtax-600 hover:text-tirtax-900 font-semibold">
                                    Update Status
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Update Status (Menggunakan AlpineJS) -->
    <div x-data="{ open: false, shipmentId: null, currentStatus: '' }"
        @open-modal.window="open = true; shipmentId = $event.detail.id; currentStatus = $event.detail.currentStatus"
        x-show="open" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">

        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="open" @click="open = false" x-transition.opacity
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <!-- Modal Panel -->
            <div x-show="open" x-transition.scale
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form :action="'/shipments/' + shipmentId + '/status'" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Update Status Pengiriman</h3>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Status Baru</label>
                            <select name="status"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-tirtax-500 focus:border-tirtax-500 p-2 border">
                                <option value="created" :selected="currentStatus === 'created'">Created (Dibuat)</option>
                                <option value="paid" :selected="currentStatus === 'paid'">Paid (Dibayar)</option>
                                <option value="picked_up" :selected="currentStatus === 'picked_up'">Picked Up (Diambil
                                    Kurir)</option>
                                <option value="in_transit" :selected="currentStatus === 'in_transit'">In Transit (Dalam
                                    Perjalanan)</option>
                                <option value="delivered" :selected="currentStatus === 'delivered'">Delivered (Diterima)
                                </option>
                                <option value="cancelled" :selected="currentStatus === 'cancelled'">Cancelled (Dibatalkan)
                                </option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Kegiatan</label>
                            <input type="text" name="description" required
                                placeholder="Contoh: Paket telah diterima oleh kurir Jakarta"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-tirtax-500 focus:border-tirtax-500 p-2 border">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi (Opsional)</label>
                            <input type="text" name="location" placeholder="Contoh: Hub Jakarta Selatan"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-tirtax-500 focus:border-tirtax-500 p-2 border">
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-tirtax-600 text-base font-medium text-white hover:bg-tirtax-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Simpan Perubahan
                        </button>
                        <button type="button" @click="open = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection