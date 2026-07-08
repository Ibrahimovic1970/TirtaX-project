@extends('layouts.app')

@section('title', 'Detail Cabang - TirtaX')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-8">

        <div class="mb-6">
            <a href="{{ route('admin.branches.index') }}"
                class="text-navy-700 hover:text-navy-900 font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Kembali ke Daftar Cabang
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-sky-100 p-8 mb-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <h1 class="text-2xl font-bold text-navy-950">{{ $branch->name }}</h1>
                        @if($branch->is_main)
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-800">🏛️ Cabang
                                Utama</span>
                        @endif
                        @if($branch->is_active)
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800">Aktif</span>
                        @else
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800">Nonaktif</span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-500">Kode: <span class="font-bold text-navy-700">{{ $branch->code }}</span>
                    </p>
                </div>
                <a href="{{ route('admin.branches.edit', $branch->id) }}"
                    class="px-5 py-2.5 bg-navy-800 text-white font-semibold rounded-lg hover:bg-navy-900 transition">
                    ✏️ Edit
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-sky-50 rounded-xl p-5 border border-sky-100">
                    <h3 class="font-bold text-navy-950 mb-3">📍 Alamat</h3>
                    <p class="text-sm text-gray-700 mb-1">{{ $branch->address }}</p>
                    <p class="text-sm text-gray-700">{{ $branch->city }}, {{ $branch->province }} {{ $branch->postal_code }}
                    </p>
                </div>
                <div class="bg-sky-50 rounded-xl p-5 border border-sky-100">
                    <h3 class="font-bold text-navy-950 mb-3">📞 Kontak</h3>
                    <p class="text-sm text-gray-700 mb-1">Telepon: {{ $branch->phone }}</p>
                    <p class="text-sm text-gray-700">Email: {{ $branch->email ?? '-' }}</p>
                </div>
                <div class="bg-sky-50 rounded-xl p-5 border border-sky-100">
                    <h3 class="font-bold text-navy-950 mb-3">👤 Penanggung Jawab</h3>
                    <p class="text-sm text-gray-700 mb-1">Nama: {{ $branch->pic_name ?? '-' }}</p>
                    <p class="text-sm text-gray-700">Telepon: {{ $branch->pic_phone ?? '-' }}</p>
                </div>
                <div class="bg-sky-50 rounded-xl p-5 border border-sky-100">
                    <h3 class="font-bold text-navy-950 mb-3">ℹ️ Informasi</h3>
                    <p class="text-sm text-gray-700 mb-1">Dibuat: {{ $branch->created_at->format('d M Y, H:i') }}</p>
                    <p class="text-sm text-gray-700">Terakhir Update: {{ $branch->updated_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection