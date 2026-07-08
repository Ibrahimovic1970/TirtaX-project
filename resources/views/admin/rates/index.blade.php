@extends('layouts.app')

@section('title', 'Master Tarif - TirtaX')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-navy-900 to-navy-800 rounded-2xl shadow-xl p-8 mb-8 text-white">
            <div class="flex justify-between items-start flex-wrap gap-4">
                <div>
                    <h1 class="text-3xl font-bold mb-2">📦 Master Tarif</h1>
                    <p class="text-sky-200">Kelola tarif pengiriman berdasarkan rute dan layanan.</p>
                </div>
                <a href="{{ route('admin.rates.create') }}"
                    class="px-6 py-3 bg-sky-300 text-navy-950 rounded-lg font-semibold hover:bg-sky-200 transition shadow-md flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Tarif Baru
                </a>
            </div>
        </div>

        {{-- Alert Success --}}
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 rounded-r-xl p-4 mb-6 shadow-md">
                <p class="text-green-700 font-medium">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Filter & Search --}}
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border border-sky-100">
            <form method="GET" action="{{ route('admin.rates.index') }}" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-navy-900 mb-2">Cari Kota</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Contoh: Jakarta, Bandung"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900">
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-navy-900 mb-2">Jenis Layanan</label>
                    <select name="service_type"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900">
                        <option value="">Semua Layanan</option>
                        <option value="REGULER" {{ request('service_type') == 'REGULER' ? 'selected' : '' }}>Reguler</option>
                        <option value="EKONOMI" {{ request('service_type') == 'EKONOMI' ? 'selected' : '' }}>Ekonomi</option>
                        <option value="EXPRESS" {{ request('service_type') == 'EXPRESS' ? 'selected' : '' }}>Express</option>
                        <option value="SAMEDAY" {{ request('service_type') == 'SAMEDAY' ? 'selected' : '' }}>Same Day</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit"
                        class="w-full md:w-auto px-6 py-3 bg-navy-800 text-white rounded-lg hover:bg-navy-900 transition font-semibold">
                        🔍 Filter
                    </button>
                </div>
            </form>
        </div>

        {{-- Tabel Tarif --}}
        <div class="bg-white rounded-2xl shadow-lg border border-sky-100 overflow-hidden">
            @if($rates->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-20 h-20 bg-sky-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-navy-950 mb-2">Belum ada tarif</h3>
                    <p class="text-gray-500 mb-6">Tambahkan tarif pengiriman pertama Anda.</p>
                    <a href="{{ route('admin.rates.create') }}"
                        class="inline-block bg-navy-800 text-white px-6 py-3 rounded-lg font-semibold hover:bg-navy-900 transition">
                        Tambah Tarif Pertama
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-sky-100">
                        <thead class="bg-navy-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">Kota Asal</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">Kota Tujuan</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">Layanan</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">Harga Dasar</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">Harga/Kg</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">Estimasi</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-navy-700 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-sky-100">
                            @foreach($rates as $rate)
                                <tr class="hover:bg-sky-50 transition">
                                    <td class="px-6 py-4 text-sm font-medium text-navy-900">{{ $rate->origin_city }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-navy-900">{{ $rate->destination_city }}</td>
                                    <td class="px-6 py-4">
                                        @php
                                            $serviceColors = [
                                                'REGULER' => 'bg-blue-100 text-blue-800',
                                                'EKONOMI' => 'bg-green-100 text-green-800',
                                                'EXPRESS' => 'bg-purple-100 text-purple-800',
                                                'SAMEDAY' => 'bg-orange-100 text-orange-800',
                                            ];
                                            $color = $serviceColors[$rate->service_type] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $color }}">
                                            {{ $rate->service_type }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold text-navy-950">Rp
                                        {{ number_format($rate->base_price, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-navy-950">Rp
                                        {{ number_format($rate->price_per_kg, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $rate->estimation_days }} hari</td>
                                    <td class="px-6 py-4">
                                        @if($rate->is_active)
                                            <span
                                                class="px-2.5 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800">Aktif</span>
                                        @else
                                            <span
                                                class="px-2.5 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('admin.rates.edit', $rate->id) }}"
                                                class="px-3 py-1.5 bg-sky-100 text-navy-700 rounded-lg text-xs font-semibold hover:bg-sky-200 transition">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.rates.destroy', $rate->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus tarif ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-xs font-semibold hover:bg-red-200 transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-sky-100">
                    {{ $rates->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection