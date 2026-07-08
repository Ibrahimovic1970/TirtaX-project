@extends('layouts.app')

@section('title', 'Master Cabang - TirtaX')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-navy-900 to-navy-800 rounded-2xl shadow-xl p-8 mb-8 text-white">
            <div class="flex justify-between items-start flex-wrap gap-4">
                <div>
                    <h1 class="text-3xl font-bold mb-2">🏢 Master Cabang</h1>
                    <p class="text-sky-200">Kelola cabang operasional TirtaX di berbagai kota.</p>
                </div>
                <a href="{{ route('admin.branches.create') }}"
                    class="px-6 py-3 bg-sky-300 text-navy-950 rounded-lg font-semibold hover:bg-sky-200 transition shadow-md flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Cabang Baru
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
            <form method="GET" action="{{ route('admin.branches.index') }}" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-navy-900 mb-2">Cari Cabang</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Kode, nama, atau kota"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900">
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-navy-900 mb-2">Status</label>
                    <select name="status"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
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

        {{-- Tabel Cabang --}}
        <div class="bg-white rounded-2xl shadow-lg border border-sky-100 overflow-hidden">
            @if($branches->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-20 h-20 bg-sky-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-navy-950 mb-2">Belum ada cabang</h3>
                    <p class="text-gray-500 mb-6">Tambahkan cabang operasional pertama Anda.</p>
                    <a href="{{ route('admin.branches.create') }}"
                        class="inline-block bg-navy-800 text-white px-6 py-3 rounded-lg font-semibold hover:bg-navy-900 transition">
                        Tambah Cabang Pertama
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-sky-100">
                        <thead class="bg-navy-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">Kode</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">Nama Cabang</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">Kota</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">Telepon</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">Status</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-navy-700 uppercase">Tipe</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-navy-700 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-sky-100">
                            @foreach($branches as $branch)
                                <tr class="hover:bg-sky-50 transition">
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-bold text-navy-900">{{ $branch->code }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-navy-950">{{ $branch->name }}</div>
                                        <div class="text-xs text-gray-500">{{ Str::limit($branch->address, 40) }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $branch->city }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $branch->phone }}</td>
                                    <td class="px-6 py-4">
                                        @if($branch->is_active)
                                            <span
                                                class="px-2.5 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800">Aktif</span>
                                        @else
                                            <span
                                                class="px-2.5 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($branch->is_main)
                                            <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-800">🏛️
                                                Utama</span>
                                        @else
                                            <span class="text-xs text-gray-400">Cabang</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('admin.branches.show', $branch->id) }}"
                                                class="px-3 py-1.5 bg-sky-100 text-navy-700 rounded-lg text-xs font-semibold hover:bg-sky-200 transition">
                                                Detail
                                            </a>
                                            <a href="{{ route('admin.branches.edit', $branch->id) }}"
                                                class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg text-xs font-semibold hover:bg-blue-200 transition">
                                                Edit
                                            </a>
                                            @if(!$branch->is_main)
                                                <form action="{{ route('admin.branches.destroy', $branch->id) }}" method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus cabang ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-xs font-semibold hover:bg-red-200 transition">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-sky-100">
                    {{ $branches->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection