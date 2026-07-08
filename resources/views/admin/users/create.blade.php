@extends('layouts.app')

@section('title', 'Tambah User - TirtaX')

@section('content')
    <div class="max-w-2xl mx-auto px-4 py-8">

        <div class="mb-6">
            <a href="{{ route('admin.users.index') }}"
                class="text-navy-700 hover:text-navy-900 font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Kembali ke Daftar User
            </a>
        </div>

        <div class="bg-gradient-to-r from-navy-900 to-navy-800 rounded-2xl shadow-xl p-8 mb-8 text-white">
            <h1 class="text-3xl font-bold mb-2">➕ Tambah User Baru</h1>
            <p class="text-sky-200">Buat akun baru untuk admin, kurir, atau customer.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-sky-100 p-8">
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-navy-900 mb-2">Nama Lengkap <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Nama lengkap"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-navy-900 mb-2">Email <span
                            class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="email@example.com"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-navy-900 mb-2">Password <span
                            class="text-red-500">*</span></label>
                    <input type="password" name="password" required placeholder="Minimal 8 karakter"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900">
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-navy-900 mb-2">Role <span
                            class="text-red-500">*</span></label>
                    <select name="role" required
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900">
                        <option value="">Pilih Role</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="courier" {{ old('role') == 'courier' ? 'selected' : '' }}>Kurir</option>
                        <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                    </select>
                    @error('role')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="flex gap-3 pt-4">
                    <a href="{{ route('admin.users.index') }}"
                        class="flex-1 text-center py-3 border-2 border-gray-300 rounded-xl text-gray-700 font-semibold hover:bg-gray-50 transition">Batal</a>
                    <button type="submit"
                        class="flex-1 py-3 bg-navy-800 text-white font-bold rounded-xl hover:bg-navy-900 transition shadow-lg">💾
                        Simpan User</button>
                </div>
            </form>
        </div>
    </div>
@endsection