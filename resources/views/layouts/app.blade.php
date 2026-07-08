<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TirtaX - Ekspedisi Online Terpercaya')</title>

    {{-- PWA Meta Tags --}}
    <meta name="theme-color" content="#0f1d4a">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="TirtaX">
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: {
                            50: '#f0f4ff',
                            100: '#dbe4ff',
                            200: '#bac8ff',
                            300: '#91a7ff',
                            400: '#748ffc',
                            500: '#5c7cfa',
                            600: '#4c6ef5',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                            950: '#0f1d4a',
                        },
                        sky: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        accent: {
                            500: '#f97316',
                            600: '#ea580c',
                        }
                    }
                }
            }
        }
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .nav-scroll::-webkit-scrollbar {
            height: 4px;
        }

        .nav-scroll::-webkit-scrollbar-track {
            background: #1e3a8a;
        }

        .nav-scroll::-webkit-scrollbar-thumb {
            background: #7dd3fc;
            border-radius: 2px;
        }
    </style>
</head>

<body class="bg-white text-gray-800 antialiased">

    {{-- NAVBAR --}}
    <nav class="bg-navy-950 shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">

                {{-- Logo --}}
                <div class="flex-shrink-0">
                    <a href="/" class="flex items-center gap-2">
                        <svg class="h-8 w-8 text-sky-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <span class="font-bold text-2xl text-white">
                            Tirta<span class="text-sky-300">X</span>
                        </span>
                    </a>
                </div>

                {{-- Center Menu --}}
                <div class="hidden lg:flex items-center flex-1 mx-8">
                    @auth
                        @if(Auth::user()->role === 'customer')
                            <div class="nav-scroll flex items-center space-x-1 overflow-x-auto mx-auto py-2">
                                <a href="{{ route('shipments.create') }}"
                                    class="flex items-center gap-2 px-3 py-2 text-sm text-gray-200 hover:text-sky-300 hover:bg-navy-800 rounded-lg transition whitespace-nowrap">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Pesan Pengiriman Baru
                                </a>
                                <a href="{{ route('shipments.my') }}"
                                    class="flex items-center gap-2 px-3 py-2 text-sm text-gray-200 hover:text-sky-300 hover:bg-navy-800 rounded-lg transition whitespace-nowrap">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                        </path>
                                    </svg>
                                    Riwayat Pesanan Saya
                                </a>
                                <a href="/"
                                    class="flex items-center gap-2 px-3 py-2 text-sm text-gray-200 hover:text-sky-300 hover:bg-navy-800 rounded-lg transition whitespace-nowrap">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                        </path>
                                    </svg>
                                    Lacak Paket Saya
                                </a>
                            </div>
                        @endif

                        @if(Auth::user()->role === 'courier')
                            {{-- Courier: Tidak ada menu di navbar --}}
                        @endif

                        @if(Auth::user()->role === 'admin')
                            <div class="nav-scroll flex items-center space-x-1 overflow-x-auto mx-auto py-2">
                                <a href="{{ route('admin.dashboard') }}"
                                    class="flex items-center gap-2 px-3 py-2 text-sm text-gray-200 hover:text-sky-300 hover:bg-navy-800 rounded-lg transition whitespace-nowrap">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                                        </path>
                                    </svg>
                                    Dashboard
                                </a>
                                <a href="{{ route('admin.shipments') }}"
                                    class="flex items-center gap-2 px-3 py-2 text-sm text-gray-200 hover:text-sky-300 hover:bg-navy-800 rounded-lg transition whitespace-nowrap">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                        </path>
                                    </svg>
                                    Kelola Pengiriman
                                </a>
                                <a href="{{ route('admin.rates.index') }}"
                                    class="flex items-center gap-2 px-3 py-2 text-sm text-gray-200 hover:text-sky-300 hover:bg-navy-800 rounded-lg transition whitespace-nowrap">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    Master Tarif
                                </a>
                                <a href="{{ route('admin.branches.index') }}"
                                    class="flex items-center gap-2 px-3 py-2 text-sm text-gray-200 hover:text-sky-300 hover:bg-navy-800 rounded-lg transition whitespace-nowrap">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                        </path>
                                    </svg>
                                    Master Cabang
                                </a>
                                <a href="{{ route('admin.users.index') }}"
                                    class="flex items-center gap-2 px-3 py-2 text-sm text-gray-200 hover:text-sky-300 hover:bg-navy-800 rounded-lg transition whitespace-nowrap">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                    Master User
                                </a>
                                <a href="{{ route('admin.audit-logs.index') }}"
                                    class="flex items-center gap-2 px-3 py-2 text-sm text-gray-200 hover:text-sky-300 hover:bg-navy-800 rounded-lg transition whitespace-nowrap">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                        </path>
                                    </svg>
                                    Audit Log
                                </a>
                                <a href="/"
                                    class="flex items-center gap-2 px-3 py-2 text-sm text-gray-200 hover:text-sky-300 hover:bg-navy-800 rounded-lg transition whitespace-nowrap">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                        </path>
                                    </svg>
                                    Lihat Website
                                </a>
                            </div>
                        @endif
                    @endauth
                </div>

                {{-- Right Menu - User Profile --}}
                <div class="flex items-center">
                    @auth
                        {{-- User Dropdown --}}
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="flex items-center gap-3 text-gray-200 hover:text-sky-300 font-medium focus:outline-none transition">
                                <div class="w-9 h-9 bg-sky-300 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-navy-950" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div class="hidden lg:flex flex-col items-start">
                                    <span class="text-sm font-semibold leading-tight">{{ Auth::user()->name }}</span>
                                    <span
                                        class="text-xs px-2 py-0.5 bg-sky-300 text-navy-950 rounded-full capitalize font-medium leading-tight">{{ Auth::user()->role }}</span>
                                </div>
                            </button>

                            {{-- Dropdown Menu --}}
                            <div x-show="open" @click.away="open = false" x-transition
                                class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-xl border border-sky-200 py-2 z-50">

                                {{-- User Info --}}
                                <div class="px-4 py-3 border-b border-sky-100">
                                    <p class="text-sm font-bold text-navy-950">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                    <p
                                        class="text-xs px-2 py-1 bg-sky-100 text-navy-700 rounded-full capitalize inline-block mt-1 font-medium">
                                        {{ Auth::user()->role }}</p>
                                </div>

                                {{-- Menu Beranda --}}
                                <a href="/"
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-navy-700 hover:bg-sky-50 transition">
                                    <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                        </path>
                                    </svg>
                                    <span class="font-semibold">Beranda</span>
                                </a>

                                {{-- Menu Dashboard berdasarkan Role --}}
                                @if(Auth::user()->role === 'customer')
                                    <a href="{{ route('customer.dashboard') }}"
                                        class="flex items-center gap-3 px-4 py-2.5 text-sm text-navy-700 hover:bg-sky-50 transition">
                                        <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                                            </path>
                                        </svg>
                                        <span class="font-semibold">Dashboard Saya</span>
                                    </a>
                                @endif

                                @if(Auth::user()->role === 'courier')
                                    <a href="{{ route('courier.dashboard') }}"
                                        class="flex items-center gap-3 px-4 py-2.5 text-sm text-navy-700 hover:bg-sky-50 transition">
                                        <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                                            </path>
                                        </svg>
                                        <span class="font-semibold">Dashboard Kurir</span>
                                    </a>
                                @endif

                                @if(Auth::user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="flex items-center gap-3 px-4 py-2.5 text-sm text-navy-700 hover:bg-sky-50 transition">
                                        <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                                            </path>
                                        </svg>
                                        <span class="font-semibold">Dashboard Admin</span>
                                    </a>
                                @endif

                                {{-- Divider --}}
                                <div class="border-t border-sky-100 my-2"></div>

                                {{-- Logout --}}
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                            </path>
                                        </svg>
                                        <span class="font-semibold">Keluar</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-3">
                            <a href="{{ route('login') }}"
                                class="text-gray-200 hover:text-sky-300 font-medium transition px-3 py-2">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}"
                                class="bg-sky-300 text-navy-950 px-5 py-2 rounded-lg font-semibold hover:bg-sky-200 transition shadow-md">
                                Daftar
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- MAIN CONTENT --}}
    <main>
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="bg-navy-950 text-white py-12 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="h-6 w-6 text-sky-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <span class="font-bold text-xl text-white">Tirta<span class="text-sky-300">X</span></span>
                    </div>
                    <p class="text-gray-300 text-sm leading-relaxed">
                        Platform ekspedisi digital terintegrasi. Kirim paket lebih cepat, aman, dan terpercaya.
                    </p>
                </div>
                <div>
                    <h4 class="font-bold text-lg mb-4 text-sky-300">Menu Cepat</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/" class="text-gray-300 hover:text-sky-300 transition">Beranda</a></li>
                        <li><a href="/track" class="text-gray-300 hover:text-sky-300 transition">Lacak Resi</a></li>
                        <li><a href="/about" class="text-gray-300 hover:text-sky-300 transition">Tentang Kami</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-lg mb-4 text-sky-300">Hubungi Kami</h4>
                    <ul class="space-y-2 text-sm text-gray-300">
                        <li> support@tirtax.com</li>
                        <li>📞 0800-123-4567</li>
                        <li>📍 Jakarta, Indonesia</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-navy-800 mt-8 pt-8 text-center text-sm text-gray-400">
                <p>&copy; {{ date('Y') }} TirtaX Logistik. All rights reserved.</p>
            </div>
        </div>
    </footer>

    {{-- PWA Service Worker Registration --}}
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then((registration) => {
                        console.log('[PWA] Service Worker registered:', registration);
                    })
                    .catch((error) => {
                        console.log('[PWA] Service Worker registration failed:', error);
                    });
            });
        }
    </script>

</body>

</html>