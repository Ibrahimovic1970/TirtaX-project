@extends('layouts.app')

@section('title', 'Install TirtaX App')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-12 bg-gradient-to-br from-navy-50 to-sky-50">
    <div class="max-w-2xl w-full">
        <div class="bg-white rounded-2xl shadow-xl border border-sky-100 p-8">

            {{-- Header --}}
            <div class="text-center mb-8">
                <div class="w-24 h-24 bg-gradient-to-br from-navy-800 to-navy-900 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <svg class="w-12 h-12 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-navy-950 mb-2">Install TirtaX App</h1>
                <p class="text-gray-600">Jadikan TirtaX sebagai aplikasi di device Anda</p>
            </div>

            {{-- Benefits --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                <div class="bg-sky-50 rounded-xl p-5 border border-sky-200">
                    <div class="w-10 h-10 bg-sky-500 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-navy-950 mb-1">Akses Cepat</h3>
                    <p class="text-sm text-gray-600">Buka aplikasi langsung dari home screen</p>
                </div>

                <div class="bg-sky-50 rounded-xl p-5 border border-sky-200">
                    <div class="w-10 h-10 bg-sky-500 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-navy-950 mb-1">Mode Offline</h3>
                    <p class="text-sm text-gray-600">Akses data yang sudah di-cache tanpa internet</p>
                </div>

                <div class="bg-sky-50 rounded-xl p-5 border border-sky-200">
                    <div class="w-10 h-10 bg-sky-500 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-navy-950 mb-1">Notifikasi</h3>
                    <p class="text-sm text-gray-600">Terima update status pengiriman real-time</p>
                </div>

                <div class="bg-sky-50 rounded-xl p-5 border border-sky-200">
                    <div class="w-10 h-10 bg-sky-500 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-navy-950 mb-1">Performa Cepat</h3>
                    <p class="text-sm text-gray-600">Loading lebih cepat dengan caching</p>
                </div>
            </div>

            {{-- Install Instructions --}}
            <div class="bg-navy-50 rounded-xl p-6 mb-6 border-2 border-navy-200">
                <h3 class="font-bold text-navy-950 mb-4">📱 Cara Install</h3>

                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-navy-800 text-white rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="font-bold">1</span>
                        </div>
                        <div>
                            <p class="font-semibold text-navy-950">Chrome/Edge (Desktop)</p>
                            <p class="text-sm text-gray-600">Klik icon install di address bar atau menu "Install TirtaX"</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-navy-800 text-white rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="font-bold">2</span>
                        </div>
                        <div>
                            <p class="font-semibold text-navy-950">Chrome (Android)</p>
                            <p class="text-sm text-gray-600">Menu (⋮) → "Add to Home Screen" atau "Install App"</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-navy-800 text-white rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="font-bold">3</span>
                        </div>
                        <div>
                            <p class="font-semibold text-navy-950">Safari (iOS)</p>
                            <p class="text-sm text-gray-600">Share button → "Add to Home Screen"</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Install Button --}}
            <button id="install-btn" class="w-full py-3 bg-gradient-to-r from-navy-800 to-navy-900 text-white font-bold rounded-lg hover:from-navy-900 hover:to-navy-950 transition shadow-lg">
                📲 Install TirtaX Sekarang
            </button>

            {{-- Back Button --}}
            <a href="/" class="block w-full py-3 border-2 border-gray-300 text-gray-700 font-bold rounded-lg hover:bg-gray-50 transition text-center mt-3">
                ← Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

<script>
let deferredPrompt;

window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;
});

document.getElementById('install-btn').addEventListener('click', async () => {
    if (deferredPrompt) {
        deferredPrompt.prompt();
        const { outcome } = await deferredPrompt.userChoice;
        if (outcome === 'accepted') {
            alert('TirtaX berhasil diinstall!');
        }
        deferredPrompt = null;
    } else {
        alert('Silakan install manual melalui menu browser Anda.');
    }
});
</script>
@endsection
