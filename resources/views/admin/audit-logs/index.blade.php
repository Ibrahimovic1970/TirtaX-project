@extends('layouts.app')

@section('title', 'Audit Log - TirtaX')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-navy-900 to-navy-800 rounded-2xl shadow-xl p-8 mb-8 text-white">
            <div class="flex justify-between items-start flex-wrap gap-4">
                <div>
                    <h1 class="text-3xl font-bold mb-2">🔒 Audit Log</h1>
                    <p class="text-sky-200">Pantau semua aktivitas pengguna di sistem TirtaX.</p>
                </div>
                <button onclick="document.getElementById('cleanupModal').classList.remove('hidden')"
                    class="px-6 py-3 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition shadow-md flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                    Cleanup Log Lama
                </button>
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

        {{-- Statistik --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 border-2 border-navy-200">
                <p class="text-sm text-gray-500 font-medium mb-1">Total Log</p>
                <p class="text-3xl font-bold text-navy-950">{{ number_format($stats['total']) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 border-2 border-green-200">
                <p class="text-sm text-gray-500 font-medium mb-1">Hari Ini</p>
                <p class="text-3xl font-bold text-green-900">{{ number_format($stats['today']) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 border-2 border-blue-200">
                <p class="text-sm text-gray-500 font-medium mb-1">Minggu Ini</p>
                <p class="text-3xl font-bold text-blue-900">{{ number_format($stats['this_week']) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 border-2 border-purple-200">
                <p class="text-sm text-gray-500 font-medium mb-1">Bulan Ini</p>
                <p class="text-3xl font-bold text-purple-900">{{ number_format($stats['this_month']) }}</p>
            </div>
        </div>

        {{-- Filter --}}
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border border-sky-100">
            <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-navy-900 mb-2">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Deskripsi, module, atau action"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900">
                </div>
                <div>
                    <label class="block text-sm font-medium text-navy-900 mb-2">Module</label>
                    <select name="module"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900">
                        <option value="">Semua Module</option>
                        <option value="Shipment" {{ request('module') == 'Shipment' ? 'selected' : '' }}>Shipment</option>
                        <option value="Rate" {{ request('module') == 'Rate' ? 'selected' : '' }}>Rate</option>
                        <option value="Branch" {{ request('module') == 'Branch' ? 'selected' : '' }}>Branch</option>
                        <option value="User" {{ request('module') == 'User' ? 'selected' : '' }}>User</option>
                        <option value="Auth" {{ request('module') == 'Auth' ? 'selected' : '' }}>Auth</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-navy-900 mb-2">Action</label>
                    <select name="action"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900">
                        <option value="">Semua Action</option>
                        <option value="create" {{ request('action') == 'create' ? 'selected' : '' }}>Create</option>
                        <option value="update" {{ request('action') == 'update' ? 'selected' : '' }}>Update</option>
                        <option value="delete" {{ request('action') == 'delete' ? 'selected' : '' }}>Delete</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit"
                        class="w-full px-6 py-3 bg-navy-800 text-white rounded-lg hover:bg-navy-900 transition font-semibold">
                        🔍 Filter
                    </button>
                </div>
            </form>
        </div>

        {{-- Tabel Audit Log --}}
        <div class="bg-white rounded-2xl shadow-lg border border-sky-100 overflow-hidden">
            @if($auditLogs->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-20 h-20 bg-sky-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-navy-950 mb-2">Tidak ada audit log</h3>
                    <p class="text-gray-500">Belum ada aktivitas yang tercatat.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-sky-100">
                        <thead class="bg-navy-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">Waktu</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">User</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">Action</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">Module</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">Deskripsi</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-navy-700 uppercase">IP Address</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-navy-700 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-sky-100">
                            @foreach($auditLogs as $log)
                                <tr class="hover:bg-sky-50 transition">
                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                        {{ $log->created_at->format('d M Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($log->user)
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 bg-navy-100 rounded-full flex items-center justify-center">
                                                    <span
                                                        class="text-navy-700 font-bold text-xs">{{ strtoupper(substr($log->user->name, 0, 1)) }}</span>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-navy-950">{{ $log->user->name }}</p>
                                                    <p class="text-xs text-gray-500">{{ ucfirst($log->user->role) }}</p>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-400">System</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $log->action_color }}">
                                            {{ strtoupper($log->action) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-navy-900">{{ $log->module }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate">{{ $log->description }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 font-mono">{{ $log->ip_address }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.audit-logs.show', $log->id) }}"
                                            class="px-3 py-1.5 bg-sky-100 text-navy-700 rounded-lg text-xs font-semibold hover:bg-sky-200 transition">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-sky-100">
                    {{ $auditLogs->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Modal Cleanup --}}
    <div id="cleanupModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-navy-950">Cleanup Audit Log</h3>
                    <p class="text-sm text-gray-500">Hapus log yang sudah lama</p>
                </div>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                <p class="text-sm text-yellow-800">
                    ⚠️ <strong>Perhatian:</strong> Tindakan ini tidak dapat dibatalkan. Log yang dihapus akan hilang
                    permanen.
                </p>
            </div>

            <form action="{{ route('admin.audit-logs.cleanup') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-navy-900 mb-2">Hapus log lebih dari (hari)</label>
                    <input type="number" name="days" value="90" min="30" max="365" required
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900">
                    <p class="text-xs text-gray-500 mt-1">Minimal 30 hari, maksimal 365 hari</p>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('cleanupModal').classList.add('hidden')"
                        class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-3 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition shadow-md">
                        Hapus Log
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('cleanupModal').addEventListener('click', function (e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
    </script>
@endsection