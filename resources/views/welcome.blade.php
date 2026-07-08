<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TirtaX - Ekspedisi Online Terpercaya</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: {
                            50: '#f0f4ff', 100: '#dbe4ff', 200: '#bac8ff',
                            300: '#91a7ff', 400: '#748ffc', 500: '#5c7cfa',
                            600: '#4c6ef5', 700: '#1d4ed8', 800: '#1e40af',
                            900: '#1e3a8a', 950: '#0f1d4a',
                        },
                        sky: {
                            50: '#f0f9ff', 100: '#e0f2fe', 200: '#bae6fd',
                            300: '#7dd3fc', 400: '#38bdf8', 500: '#0ea5e9',
                            600: '#0284c7', 700: '#0369a1', 800: '#075985',
                            900: '#0c4a6e',
                        },
                    }
                }
            }
        }
    </script>
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body class="bg-white text-gray-800 antialiased">

    {{-- NAVBAR --}}
    <nav class="bg-navy-950 shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">

                {{-- Logo --}}
                <div class="flex items-center">
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

                {{-- Desktop Menu --}}
                <div class="hidden md:flex items-center space-x-6">
                    <a href="/" class="text-gray-200 hover:text-sky-300 font-medium transition">Beranda</a>
                    <a href="#testimoni" class="text-gray-200 hover:text-sky-300 font-medium transition">Testimoni</a>
                    <a href="#faq" class="text-gray-200 hover:text-sky-300 font-medium transition">FAQ</a>
                    <a href="#contact" class="text-gray-200 hover:text-sky-300 font-medium transition">Kontak</a>

                    @auth
                        {{-- User Dropdown --}}
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="flex items-center gap-2 text-gray-200 hover:text-sky-300 font-medium focus:outline-none">
                                <div class="w-8 h-8 bg-sky-300 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-navy-950" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <span>{{ Auth::user()->name }}</span>
                            </button>

                            {{-- Dropdown Menu --}}
                            <div x-show="open" @click.away="open = false" x-transition
                                class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-xl border border-sky-200 py-2 z-50">

                                {{-- User Info --}}
                                <div class="px-4 py-3 border-b border-sky-100">
                                    <p class="text-sm font-bold text-navy-950">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
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
                        <a href="{{ route('login') }}"
                            class="text-gray-200 hover:text-sky-300 font-medium transition">Masuk</a>
                        <a href="{{ route('register') }}"
                            class="bg-sky-300 text-navy-950 px-5 py-2 rounded-lg font-semibold hover:bg-sky-200 transition shadow-md">
                            Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- HERO SECTION --}}
    <div
        class="bg-gradient-to-br from-navy-950 via-navy-900 to-navy-800 min-h-screen flex items-center justify-center px-4 py-20">
        <div class="max-w-4xl mx-auto text-center">

            {{-- Hero Title --}}
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
                Kirim Paket Lebih <span class="text-sky-300">Cepat</span> & <span class="text-sky-300">Aman</span>
            </h1>

            {{-- Hero Subtitle --}}
            <p class="text-lg md:text-xl text-sky-100 mb-12 max-w-3xl mx-auto">
                Platform ekspedisi digital terintegrasi. Cek ongkir real-time, lacak paket, dan kelola pengiriman dalam
                satu genggaman.
            </p>

            {{-- Tracking & Cek Ongkir Box --}}
            <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-2xl mx-auto" x-data="{ 
                activeTab: 'track',
                cekOngkirLoading: false,
                cekOngkirResult: null,
                trackingNumber: '',
                origin: '',
                destination: '',
                weight: ''
            }">

                {{-- Tabs --}}
                <div class="flex border-b border-gray-200 mb-6">
                    <button @click="activeTab = 'track'"
                        :class="activeTab === 'track' ? 'border-navy-800 text-navy-800' : 'border-transparent text-gray-500 hover:text-navy-700'"
                        class="flex-1 py-3 px-6 text-center font-semibold border-b-2 transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                            </path>
                        </svg>
                        Lacak Resi
                    </button>
                    <button @click="activeTab = 'ongkir'"
                        :class="activeTab === 'ongkir' ? 'border-navy-800 text-navy-800' : 'border-transparent text-gray-500 hover:text-navy-700'"
                        class="flex-1 py-3 px-6 text-center font-semibold border-b-2 transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                            </path>
                        </svg>
                        Cek Ongkir
                    </button>
                </div>

                {{-- Lacak Resi Tab --}}
                <div x-show="activeTab === 'track'">
                    <h3 class="text-2xl font-bold text-navy-950 mb-6">Lacak Pengiriman Anda</h3>

                    <form @submit.prevent="alert('Fitur tracking akan segera hadir')" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 text-left">Nomor Resi (Tracking
                                Number)</label>
                            <div class="flex gap-3">
                                <input type="text" x-model="trackingNumber" placeholder="Contoh: TRX-20260613-XXXXXX"
                                    class="flex-1 border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-4 border text-lg text-gray-900 placeholder-gray-400 bg-white">
                                <button type="submit"
                                    class="px-8 py-4 bg-navy-800 text-white font-bold rounded-lg hover:bg-navy-900 transition shadow-lg">
                                    Lacak
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-2 text-left">* Anda dapat melacak paket tanpa perlu
                                login.</p>
                        </div>
                    </form>

                    {{-- Contoh Status --}}
                    <div class="mt-8 pt-8 border-t border-gray-200 text-left">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Contoh Status Pengiriman:</h4>
                        <div class="space-y-3">
                            <div class="flex items-start gap-3">
                                <div class="w-3 h-3 bg-green-500 rounded-full mt-1.5"></div>
                                <div>
                                    <p class="font-semibold text-navy-950">Paket Diterima</p>
                                    <p class="text-sm text-gray-500">Jakarta Selatan • 24 Mei 2024, 10:00 WIB</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="w-3 h-3 bg-gray-300 rounded-full mt-1.5"></div>
                                <div>
                                    <p class="font-semibold text-navy-950">Pembuatan Resi</p>
                                    <p class="text-sm text-gray-500">Sistem TirtaX • 24 Mei 2024, 09:30 WIB</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Cek Ongkir Tab --}}
                <div x-show="activeTab === 'ongkir'">
                    <h3 class="text-2xl font-bold text-navy-950 mb-6">Cek Estimasi Ongkos Kirim</h3>

                    <form @submit.prevent="cekOngkir" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2 text-left">Kota Asal</label>
                                <select x-model="origin" required
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900 bg-white">
                                    <option value="">Pilih Kota Asal</option>
                                    <option value="Jakarta">Jakarta</option>
                                    <option value="Bandung">Bandung</option>
                                    <option value="Surabaya">Surabaya</option>
                                    <option value="Yogyakarta">Yogyakarta</option>
                                    <option value="Semarang">Semarang</option>
                                    <option value="Medan">Medan</option>
                                    <option value="Denpasar">Denpasar</option>
                                    <option value="Makassar">Makassar</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2 text-left">Kota
                                    Tujuan</label>
                                <select x-model="destination" required
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900 bg-white">
                                    <option value="">Pilih Kota Tujuan</option>
                                    <option value="Jakarta">Jakarta</option>
                                    <option value="Bandung">Bandung</option>
                                    <option value="Surabaya">Surabaya</option>
                                    <option value="Yogyakarta">Yogyakarta</option>
                                    <option value="Semarang">Semarang</option>
                                    <option value="Medan">Medan</option>
                                    <option value="Denpasar">Denpasar</option>
                                    <option value="Makassar">Makassar</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 text-left">Berat Paket
                                (Kg)</label>
                            <input type="number" x-model="weight" placeholder="Contoh: 2" step="0.1" min="0.1" required
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900 placeholder-gray-400 bg-white">
                        </div>

                        <button type="submit" :disabled="cekOngkirLoading"
                            class="w-full py-4 bg-navy-800 text-white font-bold rounded-lg hover:bg-navy-900 transition shadow-lg disabled:bg-gray-400 disabled:cursor-not-allowed">
                            <span x-show="!cekOngkirLoading">Hitung Estimasi Ongkir</span>
                            <span x-show="cekOngkirLoading">Menghitung...</span>
                        </button>
                    </form>

                    {{-- Hasil Perhitungan --}}
                    <div x-show="cekOngkirResult" class="mt-6 bg-sky-50 border-2 border-sky-200 rounded-xl p-6">
                        <h4 class="text-lg font-bold text-navy-950 mb-4">Hasil Perhitungan</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Rute:</span>
                                <span class="font-semibold text-navy-900" x-text="cekOngkirResult?.route"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Berat:</span>
                                <span class="font-semibold text-navy-900"
                                    x-text="cekOngkirResult?.weight + ' Kg'"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Estimasi:</span>
                                <span class="font-semibold text-navy-900" x-text="cekOngkirResult?.estimation"></span>
                            </div>
                            <div class="border-t border-sky-200 pt-3 mt-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold text-gray-700">Total Ongkir:</span>
                                    <span class="text-3xl font-bold text-accent-600"
                                        x-text="'Rp ' + cekOngkirResult?.total_cost.toLocaleString('id-ID')"></span>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('register') }}"
                            class="mt-4 block w-full text-center py-3 bg-sky-600 text-white font-bold rounded-lg hover:bg-sky-700 transition">
                            Daftar Sekarang untuk Kirim
                        </a>
                    </div>
                </div>
            </div>

            {{-- Stats Section --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12 max-w-3xl mx-auto">
                <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-xl p-6">
                    <div class="text-4xl font-bold text-sky-300 mb-2">10K+</div>
                    <div class="text-sky-100">Pengiriman Sukses</div>
                </div>
                <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-xl p-6">
                    <div class="text-4xl font-bold text-sky-300 mb-2">5K+</div>
                    <div class="text-sky-100">Customer Puas</div>
                </div>
                <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-xl p-6">
                    <div class="text-4xl font-bold text-sky-300 mb-2">50+</div>
                    <div class="text-sky-100">Kota Terjangkau</div>
                </div>
            </div>
        </div>
    </div>

    {{-- TESTIMONI SECTION --}}
    <section id="testimoni" class="py-20 bg-sky-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-navy-950 mb-4">Apa Kata Mereka?</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Testimoni dari pelanggan yang telah menggunakan
                    layanan TirtaX</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Testimoni 1 --}}
                <div class="bg-white rounded-2xl shadow-lg p-8 border border-sky-100 hover:shadow-xl transition">
                    <div class="flex items-center gap-1 mb-4">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>
                        @endfor
                    </div>
                    <p class="text-gray-700 mb-6 italic">"Pelayanan sangat memuaskan! Paket sampai tepat waktu dan
                        kondisi aman. Tracking real-time sangat membantu."</p>
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 bg-navy-800 rounded-full flex items-center justify-center text-white font-bold">
                            A</div>
                        <div>
                            <p class="font-bold text-navy-950">Ahmad Ibrahimovic</p>
                            <p class="text-sm text-gray-500">Jakarta</p>
                        </div>
                    </div>
                </div>

                {{-- Testimoni 2 --}}
                <div class="bg-white rounded-2xl shadow-lg p-8 border border-sky-100 hover:shadow-xl transition">
                    <div class="flex items-center gap-1 mb-4">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>
                        @endfor
                    </div>
                    <p class="text-gray-700 mb-6 italic">"Harga sangat kompetitif dan proses pengiriman mudah. Sangat
                        cocok untuk bisnis online shop saya. Recommended!"</p>
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 bg-sky-600 rounded-full flex items-center justify-center text-white font-bold">
                            S</div>
                        <div>
                            <p class="font-bold text-navy-950">Siti Nurhaliza</p>
                            <p class="text-sm text-gray-500">Bandung</p>
                        </div>
                    </div>
                </div>

                {{-- Testimoni 3 --}}
                <div class="bg-white rounded-2xl shadow-lg p-8 border border-sky-100 hover:shadow-xl transition">
                    <div class="flex items-center gap-1 mb-4">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>
                        @endfor
                    </div>
                    <p class="text-gray-700 mb-6 italic">"Kurir profesional dan ramah. Sistem tracking sangat akurat.
                        TirtaX jadi pilihan utama saya untuk kirim dokumen penting."</p>
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold">
                            B</div>
                        <div>
                            <p class="font-bold text-navy-950">Budi Santoso</p>
                            <p class="text-sm text-gray-500">Surabaya</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FAQ SECTION --}}
    <section id="faq" class="py-20 bg-white">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-navy-950 mb-4">Pertanyaan yang Sering Diajukan</h2>
                <p class="text-lg text-gray-600">Temukan jawaban atas pertanyaan umum tentang layanan TirtaX</p>
            </div>

            <div class="space-y-4" x-data="{ active: null }">
                {{-- FAQ 1 --}}
                <div class="bg-sky-50 rounded-xl shadow-md overflow-hidden border border-sky-100">
                    <button @click="active = active === 1 ? null : 1"
                        class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-sky-100 transition">
                        <span class="font-bold text-navy-950">Bagaimana cara mengirim paket?</span>
                        <svg class="w-5 h-5 text-navy-700 transform transition-transform"
                            :class="active === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div x-show="active === 1" x-collapse class="px-6 pb-4 text-gray-600">
                        <ol class="list-decimal list-inside space-y-2">
                            <li>Daftar atau login ke akun TirtaX</li>
                            <li>Klik "Kirim Paket" dan isi detail pengiriman</li>
                            <li>Sistem akan menghitung ongkir otomatis</li>
                            <li>Lakukan pembayaran</li>
                            <li>Kurir akan menjemput paket Anda</li>
                        </ol>
                    </div>
                </div>

                {{-- FAQ 2 --}}
                <div class="bg-sky-50 rounded-xl shadow-md overflow-hidden border border-sky-100">
                    <button @click="active = active === 2 ? null : 2"
                        class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-sky-100 transition">
                        <span class="font-bold text-navy-950">Berapa lama estimasi pengiriman?</span>
                        <svg class="w-5 h-5 text-navy-700 transform transition-transform"
                            :class="active === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div x-show="active === 2" x-collapse class="px-6 pb-4 text-gray-600">
                        <p>Estimasi pengiriman tergantung jarak dan layanan yang dipilih:</p>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            <li>Same Day: Hari yang sama</li>
                            <li>Express: 1-2 hari</li>
                            <li>Reguler: 3-5 hari</li>
                            <li>Ekonomi: 5-7 hari</li>
                        </ul>
                    </div>
                </div>

                {{-- FAQ 3 --}}
                <div class="bg-sky-50 rounded-xl shadow-md overflow-hidden border border-sky-100">
                    <button @click="active = active === 3 ? null : 3"
                        class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-sky-100 transition">
                        <span class="font-bold text-navy-950">Apakah paket saya diasuransikan?</span>
                        <svg class="w-5 h-5 text-navy-700 transform transition-transform"
                            :class="active === 3 ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div x-show="active === 3" x-collapse class="px-6 pb-4 text-gray-600">
                        <p>Ya, semua paket yang dikirim melalui TirtaX otomatis mendapat asuransi dasar. Untuk barang
                            bernilai tinggi, Anda dapat menambahkan asuransi tambahan.</p>
                    </div>
                </div>

                {{-- FAQ 4 --}}
                <div class="bg-sky-50 rounded-xl shadow-md overflow-hidden border border-sky-100">
                    <button @click="active = active === 4 ? null : 4"
                        class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-sky-100 transition">
                        <span class="font-bold text-navy-950">Metode pembayaran apa saja yang tersedia?</span>
                        <svg class="w-5 h-5 text-navy-700 transform transition-transform"
                            :class="active === 4 ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div x-show="active === 4" x-collapse class="px-6 pb-4 text-gray-600">
                        <p>Kami menerima berbagai metode pembayaran:</p>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            <li>Transfer Bank (BCA, Mandiri, BNI, BRI)</li>
                            <li>E-Wallet (GoPay, OVO, DANA, LinkAja)</li>
                            <li>Virtual Account</li>
                            <li>Credit Card/Debit Card</li>
                            <li>Indomaret/Alfamart</li>
                        </ul>
                    </div>
                </div>

                {{-- FAQ 5 --}}
                <div class="bg-sky-50 rounded-xl shadow-md overflow-hidden border border-sky-100">
                    <button @click="active = active === 5 ? null : 5"
                        class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-sky-100 transition">
                        <span class="font-bold text-navy-950">Bagaimana cara melacak paket?</span>
                        <svg class="w-5 h-5 text-navy-700 transform transition-transform"
                            :class="active === 5 ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div x-show="active === 5" x-collapse class="px-6 pb-4 text-gray-600">
                        <p>Anda dapat melacak paket dengan:</p>
                        <ol class="list-decimal list-inside mt-2 space-y-1">
                            <li>Masuk ke menu "Lacak Resi" di homepage</li>
                            <li>Masukkan nomor resi yang Anda terima</li>
                            <li>Klik "Lacak" untuk melihat status pengiriman</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CONTACT SECTION --}}
    <section id="contact" class="py-20 bg-navy-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-navy-950 mb-4">Hubungi Kami</h2>
                <p class="text-lg text-gray-600">Tim kami siap membantu Anda. Jangan ragu untuk menghubungi kami.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                {{-- Contact Info --}}
                <div>
                    <h3 class="text-2xl font-bold text-navy-950 mb-6">Informasi Kontak</h3>

                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-sky-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-navy-800" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-navy-950 mb-1">Alamat Kantor</h4>
                                <p class="text-gray-600">Jl. Sudirman No. 123<br>Jakarta Selatan, DKI Jakarta 12190</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-sky-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-navy-800" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-navy-950 mb-1">Telepon</h4>
                                <p class="text-gray-600">0800-123-4567 (Gratis)<br>+62 21 1234 5678</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-sky-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-navy-800" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-navy-950 mb-1">Email</h4>
                                <p class="text-gray-600">support@tirtax.com<br>cs@tirtax.com</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-sky-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-navy-800" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-navy-950 mb-1">Jam Operasional</h4>
                                <p class="text-gray-600">Senin - Jumat: 08.00 - 17.00 WIB<br>Sabtu: 08.00 - 12.00 WIB
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Social Media --}}
                    <div class="mt-8">
                        <h4 class="font-bold text-navy-950 mb-4">Ikuti Kami</h4>
                        <div class="flex gap-4">
                            <a href="#"
                                class="w-10 h-10 bg-navy-800 rounded-full flex items-center justify-center text-white hover:bg-navy-900 transition">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                </svg>
                            </a>
                            <a href="#"
                                class="w-10 h-10 bg-navy-800 rounded-full flex items-center justify-center text-white hover:bg-navy-900 transition">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                                </svg>
                            </a>
                            <a href="#"
                                class="w-10 h-10 bg-navy-800 rounded-full flex items-center justify-center text-white hover:bg-navy-900 transition">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Contact Form --}}
                <div class="bg-white rounded-2xl shadow-lg p-8 border border-sky-100">
                    <h3 class="text-2xl font-bold text-navy-950 mb-6">Kirim Pesan</h3>
                    <form class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-navy-900 mb-2">Nama Lengkap</label>
                            <input type="text" placeholder="Masukkan nama Anda"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900 placeholder-gray-400 bg-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-navy-900 mb-2">Email</label>
                            <input type="email" placeholder="email@example.com"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900 placeholder-gray-400 bg-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-navy-900 mb-2">Subjek</label>
                            <input type="text" placeholder="Subjek pesan"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900 placeholder-gray-400 bg-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-navy-900 mb-2">Pesan</label>
                            <textarea rows="4" placeholder="Tulis pesan Anda..."
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900 placeholder-gray-400 bg-white"></textarea>
                        </div>
                        <button type="button"
                            class="w-full py-3 bg-navy-800 text-white font-bold rounded-lg hover:bg-navy-900 transition shadow-lg">
                            Kirim Pesan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-navy-950 text-white py-8 border-t border-navy-800">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-gray-400 text-sm">&copy; {{ date('Y') }} TirtaX Logistik. All rights reserved.</p>
        </div>
    </footer>

    {{-- JavaScript untuk Cek Ongkir --}}
    <script>
        function cekOngkir() {
            this.cekOngkirLoading = true;

            // Simulasi API call
            setTimeout(() => {
                const baseRates = {
                    'Jakarta-Bandung': { base: 15000, perKg: 5000, days: '1-2 hari' },
                    'Jakarta-Surabaya': { base: 20000, perKg: 8000, days: '2-3 hari' },
                    'Jakarta-Yogyakarta': { base: 18000, perKg: 7000, days: '2-3 hari' },
                    'Jakarta-Semarang': { base: 18000, perKg: 7000, days: '2-3 hari' },
                    'Jakarta-Medan': { base: 25000, perKg: 10000, days: '3-4 hari' },
                    'Jakarta-Denpasar': { base: 22000, perKg: 9000, days: '3-4 hari' },
                    'Jakarta-Makassar': { base: 28000, perKg: 12000, days: '4-5 hari' },
                    'Bandung-Surabaya': { base: 22000, perKg: 8000, days: '2-3 hari' },
                    'Bandung-Yogyakarta': { base: 20000, perKg: 7000, days: '2-3 hari' },
                    'Surabaya-Makassar': { base: 25000, perKg: 10000, days: '3-4 hari' },
                };

                const route = `${this.origin}-${this.destination}`;
                const reverseRoute = `${this.destination}-${this.origin}`;

                let rate = baseRates[route] || baseRates[reverseRoute] || { base: 20000, perKg: 8000, days: '3-5 hari' };

                const weight = parseFloat(this.weight);
                const totalCost = rate.base + (rate.perKg * weight);

                this.cekOngkirResult = {
                    route: `${this.origin} → ${this.destination}`,
                    weight: weight,
                    estimation: rate.days,
                    total_cost: Math.round(totalCost)
                };

                this.cekOngkirLoading = false;
            }, 1000);
        }
    </script>

</body>

</html>