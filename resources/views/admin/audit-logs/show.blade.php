@extends('layouts.app')

@section('title', 'Detail Audit Log - TirtaX')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-8">

        <div class="mb-6">
            <a href="{{ route('admin.audit-logs.index') }}"
                class="text-navy-700 hover:text-navy-900 font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Kembali ke Daftar Audit Log
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-sky-100 p-8">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-navy-950 mb-2">Detail Audit Log</h1>
                    <p class="text-sm text-gray-500">ID: #{{ $auditLog->id }}</p>
                </div>
                <span class="px-3 py-1.5 text-sm font-bold rounded-full {{ $auditLog->action_color }}">
                    {{ strtoupper($auditLog->action) }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-sky-50 rounded-xl p-5 border border-sky-100">
                    <h3 class="font-bold text-navy-950 mb-3">👤 Informasi User</h3>
                    @if($auditLog->user)
                        <p class="text-sm text-gray-700 mb-1"><strong>Nama:</strong> {{ $auditLog->user->name }}</p>
                        <p class="text-sm text-gray-700 mb-1"><strong>Email:</strong> {{ $auditLog->user->email }}</p>
                        <p class="text-sm text-gray-700"><strong>Role:</strong> {{ ucfirst($auditLog->user->role) }}</p>
                    @else
                        <p class="text-sm text-gray-500">System / Guest</p>
                    @endif
                </div>
                <div class="bg-sky-50 rounded-xl p-5 border border-sky-100">
                    <h3 class="font-bold text-navy-950 mb-3">🌐 Informasi Request</h3>
                    <p class="text-sm text-gray-700 mb-1"><strong>IP Address:</strong> {{ $auditLog->ip_address }}</p>
                    <p class="text-sm text-gray-700 mb-1"><strong>Module:</strong> {{ $auditLog->module }}</p>
                    <p class="text-sm text-gray-700"><strong>Waktu:</strong>
                        {{ $auditLog->created_at->format('d M Y, H:i:s') }} WIB</p>
                </div>
            </div>

            <div class="bg-sky-50 rounded-xl p-5 border border-sky-100 mb-6">
                <h3 class="font-bold text-navy-950 mb-3">📝 Deskripsi</h3>
                <p class="text-sm text-gray-700">{{ $auditLog->description }}</p>
            </div>

            @if($auditLog->old_values)
                <div class="bg-red-50 rounded-xl p-5 border border-red-100 mb-6">
                    <h3 class="font-bold text-red-900 mb-3"> Nilai Lama (Before)</h3>
                    <pre
                        class="text-sm text-red-700 overflow-x-auto">{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            @endif

            @if($auditLog->new_values)
                <div class="bg-green-50 rounded-xl p-5 border border-green-100 mb-6">
                    <h3 class="font-bold text-green-900 mb-3"> Nilai Baru (After)</h3>
                    <pre
                        class="text-sm text-green-700 overflow-x-auto">{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            @endif

            <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                <h3 class="font-bold text-gray-900 mb-3">💻 User Agent</h3>
                <p class="text-xs text-gray-600 font-mono break-all">{{ $auditLog->user_agent }}</p>
            </div>
        </div>
    </div>
@endsection