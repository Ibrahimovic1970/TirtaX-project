@extends('layouts.app')
@section('content')
    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="bg-white rounded-xl shadow p-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Selamat Datang, {{ Auth::user()->name }}!</h1>
            <p class="text-gray-600 mb-8">Apa yang ingin Anda lakukan hari ini?</p>
            <div class="flex justify-center gap-4">
                <!-- Tambahkan link ini di dalam div flex justify-center gap-4 -->
                <a href="{{ route('shipments.my') }}"
                    class="bg-gray-800 text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-900 transition">
                    Lihat Riwayat Pengiriman Saya
                </a>
                <a href="{{ route('shipments.create') }}"
                    class="bg-tirtax-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-tirtax-700 transition">
                    + Buat Pengiriman Baru
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button
                        class="bg-gray-200 text-gray-800 px-8 py-3 rounded-lg font-semibold hover:bg-gray-300 transition">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection