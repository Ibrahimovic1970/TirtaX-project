@extends('layouts.app')

@section('title', 'Daftar Akun - TirtaX')

@section('content')
    <div class="min-h-[80vh] flex items-center justify-center px-4 py-12 bg-gradient-to-br from-navy-50 to-sky-50">
        <div class="max-w-md w-full">
            <div class="bg-white rounded-2xl shadow-xl border border-sky-100 p-8">

                {{-- Header --}}
                <div class="text-center mb-8">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-navy-800 to-navy-900 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <svg class="w-8 h-8 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                            </path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-navy-950 mb-2">Daftar Akun TirtaX</h1>
                    <p class="text-gray-600">
                        Sudah punya akun?
                        <a href="{{ route('login') }}"
                            class="text-navy-700 hover:text-navy-900 font-semibold transition">Masuk di sini</a>
                    </p>
                </div>

                {{-- Error Messages --}}
                @if($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 rounded-r-xl p-4 mb-6">
                        <div class="flex">
                            <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="flex-1">
                                <h3 class="text-sm font-semibold text-red-800">Terjadi kesalahan:</h3>
                                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Success Message --}}
                @if(session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 rounded-r-xl p-4 mb-6">
                        <p class="text-green-700 font-medium">{{ session('success') }}</p>
                    </div>
                @endif

                {{-- Form Register --}}
                <form action="{{ route('register') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Nama Lengkap --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-navy-900 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900 placeholder-gray-400 bg-white"
                            placeholder="Masukkan nama lengkap Anda">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Alamat Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-navy-900 mb-2">
                            Alamat Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900 placeholder-gray-400 bg-white"
                            placeholder="email@example.com">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-navy-900 mb-2">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" id="password" name="password" required
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900 placeholder-gray-400 bg-white"
                            placeholder="Minimal 8 karakter">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-navy-900 mb-2">
                            Konfirmasi Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900 placeholder-gray-400 bg-white"
                            placeholder="Ulangi password Anda">
                    </div>

                    {{-- Terms & Conditions --}}
                    <div class="flex items-start gap-3">
                        <input type="checkbox" id="terms" name="terms" required
                            class="h-4 w-4 text-navy-700 focus:ring-navy-700 border-gray-300 rounded mt-1">
                        <label for="terms" class="text-sm text-gray-600">
                            Saya menyetujui <a href="#" class="text-navy-700 hover:text-navy-900 font-semibold">Syarat &
                                Ketentuan</a>
                            serta <a href="#" class="text-navy-700 hover:text-navy-900 font-semibold">Kebijakan Privasi</a>
                            TirtaX
                        </label>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit"
                        class="w-full py-3 bg-gradient-to-r from-navy-800 to-navy-900 text-white font-bold rounded-lg hover:from-navy-900 hover:to-navy-950 transition shadow-lg transform hover:scale-[1.02] active:scale-[0.98]">
                        Daftar Sekarang
                    </button>
                </form>

                {{-- Divider --}}
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                </div>
            </div>

            {{-- Footer Info --}}
            <div class="text-center mt-6">
                <p class="text-xs text-gray-500">
                    Dengan mendaftar, Anda setuju untuk menerima email dari TirtaX.
                    Anda dapat berhenti berlangganan kapan saja.
                </p>
            </div>
        </div>
    </div>
@endsection