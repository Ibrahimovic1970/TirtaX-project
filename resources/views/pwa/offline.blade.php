@extends('layouts.app')

@section('title', 'Offline - TirtaX')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-12 bg-gradient-to-br from-navy-50 to-sky-50">
    <div class="max-w-md w-full text-center">
        <div class="bg-white rounded-2xl shadow-xl border border-sky-100 p-8">

            {{-- Offline Icon --}}
            <div class="mb-6">
                <div class="w-24 h-24 bg-gradient-to-br from-gray-400 to-gray-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636a9 9 0 010 12.728m0 0l-2.829-2.829m2.829 2.829L21 21M15.536 8.464a5 5 0 010 7.072m0 0l-2.829-2.829m-4.243 2.829a4.978 4.978 0 01-1.414-2.83m-1.414 5.658a9 9 0 01-2.167-9.238m7.824 2.167a1 1 0 111.414 1.414m-1.414-1.414L3 3m8.293 8.293l1.414 1.414"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-navy-950 mb-2">Anda Offline</h1>
                <p class="text-gray-600">Tidak ada koneksi internet</p>
            </div>

            {{-- Message --}}
            <div class="bg-sky-50 rounded-xl p-6 mb-6 border border-sky-200">
                <p class="text-sm text-gray-700 mb-4">
                    Sepertinya Anda kehilangan koneksi internet. Beberapa fitur mungkin tidak tersedia.
                </p>
                <ul class="text-left text-sm text-gray-600 space-y-2">
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Melihat riwayat pengiriman yang sudah di-cache</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Melihat detail pengiriman terakhir</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span>Membuat pengiriman baru (butuh internet)</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span>Melakukan pembayaran (butuh internet)</span>
                    </li>
                </ul>
            </div>

            {{-- Retry Button --}}
            <button onclick="window.location.reload()"
                    class="w-full py-3 bg-gradient-to-r from-navy-800 to-navy-900 text-white font-bold rounded-lg hover:from-navy-900 hover:to-navy-950 transition shadow-lg mb-3">
                🔄 Coba Lagi
            </button>

            {{-- Back Button --}}
            <a href="/" class="block w-full py-3 border-2 border-gray-300 text-gray-700 font-bold rounded-lg hover:bg-gray-50 transition text-center">
                ← Kembali ke Beranda
            </a>

            {{-- Status Indicator --}}
            <div class="mt-6 flex items-center justify-center gap-2 text-sm text-gray-500">
                <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
                <span>Status: Offline</span>
            </div>
        </div>
    </div>
</div>

<script>
// Check connection status
window.addEventListener('online', () => {
    window.location.reload();
});

window.addEventListener('offline', () => {
    console.log('You are offline');
});
</script>
@endsection
