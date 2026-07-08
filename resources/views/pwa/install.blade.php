@extends('layouts.app')

@section('title', 'Install TirtaX App')

@section('content')
    <div
        class="min-h-screen bg-gradient-to-br from-navy-950 via-navy-900 to-navy-800 flex items-center justify-center px-4 py-12">
        <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl p-8 text-center">
            {{-- Logo --}}
            <div
                class="w-24 h-24 bg-gradient-to-br from-navy-800 to-navy-900 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                <svg class="w-12 h-12 text-sky-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>

            <h1 class="text-3xl font-bold text-navy-950 mb-4">Install TirtaX</h1>
            <p class="text-gray-600 mb-8">
                Pasang aplikasi TirtaX di perangkat Anda untuk akses lebih cepat dan mudah, bahkan saat offline!
            </p>

            {{-- Features --}}
            <div class="space-y-4 mb-8 text-left">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-navy-950">Akses Offline</h3>
                        <p class="text-sm text-gray-600">Lacak paket tanpa koneksi internet</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-navy-950">Lebih Cepat</h3>
                        <p class="text-sm text-gray-600">Akses langsung dari home screen</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-navy-950">Notifikasi Real-time</h3>
                        <p class="text-sm text-gray-600">Dapatkan update status pengiriman</p>
                    </div>
                </div>
            </div>

            {{-- Install Button --}}
            <button onclick="installPWA()" id="installButton"
                class="w-full py-4 bg-gradient-to-r from-navy-800 to-navy-900 text-white font-bold rounded-xl hover:from-navy-900 hover:to-navy-950 transition shadow-lg flex items-center justify-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Install Aplikasi
            </button>

            <p class="text-xs text-gray-500 mt-4">
                Atau gunakan menu browser Anda: <strong>⋮</strong> → "Add to Home Screen"
            </p>

            <a href="/" class="inline-block mt-6 text-navy-700 hover:text-navy-900 font-semibold text-sm">
                ← Kembali ke Beranda
            </a>
        </div>
    </div>

    <script>
        let deferredPrompt;

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            console.log('[PWA] Install prompt ready');
        });

        async function installPWA() {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                console.log(`[PWA] User choice: ${outcome}`);
                deferredPrompt = null;
            } else {
                alert('Fitur install tidak tersedia di browser ini. Silakan gunakan Chrome atau Edge.');
            }
        }

        window.addEventListener('appinstalled', () => {
            console.log('[PWA] App installed successfully');
            alert('Aplikasi TirtaX berhasil diinstall! 🎉');
        });
    </script>
@endsection