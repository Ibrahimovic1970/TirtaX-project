@extends('layouts.app')

@section('title', 'Master User - TirtaX')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">

        <div class="bg-gradient-to-r from-navy-900 to-navy-800 rounded-2xl shadow-xl p-8 mb-8 text-white">
            <div class="flex justify-between items-start flex-wrap gap-4">
                <div>
                    <h1 class="text-3xl font-bold mb-2">👥 Master User</h1>
                    <p class="text-sky-200">Kelola semua user di sistem TirtaX.</p>
                </div>
                <a href="{{ route('admin.users.create') }}"
                    class="px-6 py-3 bg-sky-300 text-navy-950 rounded-lg font-semibold hover:bg-sky-200 transition shadow-md flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah User Baru
                </a>
            </div>
        </div>

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

        <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border border-sky-100">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-navy-900 mb-2">Cari User</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau email"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900">
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-navy-900 mb-2">Role</label>
                    <select name="role"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900">
                        <option value="">Semua Role</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="courier" {{ request('role') == 'courier' ? 'selected' : '' }}>Kurir</option>
                        <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit"
                        class="w-full md:w-auto px-6 py-3 bg-navy-800 text-white rounded-lg hover:bg-navy-900 transition font-semibold">🔍
                        Filter</button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-sky-100 overflow-hidden">
            @if($users->isEmpty())
                <div class="p-12 text-center">
                    <p class="text-gray-500">Tidak ada user ditemukan.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-sky-100">
                        <thead class="bg-navy-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">Terdaftar</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-navy-700 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-sky-100">
                            @foreach($users as $user)
                                <tr class="hover:bg-sky-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-navy-100 rounded-full flex items-center justify-center">
                                                <span
                                                    class="text-navy-700 font-bold text-sm">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                            </div>
                                            <span class="text-sm font-semibold text-navy-950">{{ $user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $user->email }}</td>
                                    <td class="px-6 py-4">
                                        @php
                                            $roleColors = [
                                                'admin' => 'bg-red-100 text-red-800',
                                                'courier' => 'bg-purple-100 text-purple-800',
                                                'customer' => 'bg-sky-100 text-navy-800',
                                            ];
                                            $color = $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $color }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $user->created_at->format('d M Y') }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('admin.users.edit', $user->id) }}"
                                                class="px-3 py-1.5 bg-sky-100 text-navy-700 rounded-lg text-xs font-semibold hover:bg-sky-200 transition">Edit</a>
                                            @if($user->id !== Auth::id())
                                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-xs font-semibold hover:bg-red-200 transition">Hapus</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-sky-100">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection