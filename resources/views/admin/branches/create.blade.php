@extends('layouts.app')

@section('title', 'Tambah Cabang - TirtaX')

@section('content')
    <div class="max-w-3xl mx-auto px-4 py-8">

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

        <div class="bg-gradient-to-r from-navy-900 to-navy-800 rounded-2xl shadow-xl p-8 mb-8 text-white">
            <h1 class="text-3xl font-bold mb-2">➕ Tambah Cabang Baru</h1>
            <p class="text-sky-200">Tambahkan cabang operasional TirtaX di kota baru.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-sky-100 p-8">
            <form action="{{ route('admin.branches.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-navy-900 mb-2">Kode Cabang <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="code" value="{{ old('code') }}" required placeholder="Contoh: JKT001"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900">
                        @error('code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-navy-900 mb-2">Nama Cabang <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            placeholder="Contoh: TirtaX Jakarta Pusat"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-navy-900 mb-2">Alamat Lengkap <span
                            class="text-red-500">*</span></label>
                    <textarea name="address" rows="2" required placeholder="Jl. Sudirman No. 123"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900">{{ old('address') }}</textarea>
                    @error('address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-navy-900 mb-2">Kota <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="city" value="{{ old('city') }}" required placeholder="Jakarta"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900">
                        @error('city')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-navy-900 mb-2">Provinsi <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="province" value="{{ old('province') }}" required placeholder="DKI Jakarta"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900">
                        @error('province')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-navy-900 mb-2">Kode Pos <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="postal_code" value="{{ old('postal_code') }}" required placeholder="10110"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900">
                        @error('postal_code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-navy-900 mb-2">Telepon <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="021-1234567"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900">
                        @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-navy-900 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="cabang@tirtax.com"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900">
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-navy-900 mb-2">Nama PIC</label>
                        <input type="text" name="pic_name" value="{{ old('pic_name') }}" placeholder="Nama penanggung jawab"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-navy-900 mb-2">Telepon PIC</label>
                        <input type="text" name="pic_phone" value="{{ old('pic_phone') }}" placeholder="08123456789"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900">
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                            class="h-5 w-5 text-navy-700 focus:ring-navy-700 border-gray-300 rounded">
                        <span class="text-sm font-medium text-navy-900">Cabang Aktif</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_main" value="1" {{ old('is_main') ? 'checked' : '' }}
                            class="h-5 w-5 text-navy-700 focus:ring-navy-700 border-gray-300 rounded">
                        <span class="text-sm font-medium text-navy-900">Cabang Utama</span>
                    </label>
                </div>

                <div class="flex gap-3 pt-4">
                    <a href="{{ route('admin.branches.index') }}"
                        class="flex-1 text-center py-3 border-2 border-gray-300 rounded-xl text-gray-700 font-semibold hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="flex-1 py-3 bg-navy-800 text-white font-bold rounded-xl hover:bg-navy-900 transition shadow-lg">
                        💾 Simpan Cabang
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection