@extends('layouts.app')

@section('title', 'Login - TirtaX')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-12 bg-gradient-to-br from-navy-50 to-sky-50">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-2xl shadow-xl border border-sky-100 p-8">

            {{-- Header --}}
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-gradient-to-br from-navy-800 to-navy-900 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <svg class="w-8 h-8 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-navy-950 mb-2">Selamat Datang di TirtaX</h1>
                <p class="text-gray-600">Silakan masuk ke akun Anda</p>
            </div>

            {{-- Error Messages --}}
            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 rounded-r-xl p-4 mb-6">
                    <p class="text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 rounded-r-xl p-4 mb-6">
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            @endif

            {{-- Form Login --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-navy-900 mb-2">
                        Alamat Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
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
                    <input type="password" id="password" name="password" required autocomplete="current-password"
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900 placeholder-gray-400 bg-white"
                           placeholder="Masukkan password Anda">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember Me & Forgot Password --}}
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember"
                               class="h-4 w-4 text-navy-700 focus:ring-navy-700 border-gray-300 rounded">
                        <label for="remember" class="ml-2 text-sm text-gray-600">
                            Ingat Saya
                        </label>
                    </div>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-navy-700 hover:text-navy-900 font-semibold">
                            Lupa Password?
                        </a>
                    @endif
                </div>

                {{-- Submit Button --}}
                <button type="submit"
                        class="w-full py-3 bg-gradient-to-r from-navy-800 to-navy-900 text-white font-bold rounded-lg hover:from-navy-900 hover:to-navy-950 transition shadow-lg transform hover:scale-[1.02] active:scale-[0.98]">
                    Masuk
                </button>
            </form>

            {{-- Divider --}}
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-white text-gray-500">atau</span>
                </div>
            </div>

            {{-- Register Link --}}
            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-navy-700 hover:text-navy-900 font-semibold">
                        Daftar Sekarang
                    </a>
                </p>
            </div>
        </div>

        {{-- Footer Info --}}
        <div class="text-center mt-6">
            <p class="text-xs text-gray-500">
                &copy; {{ date('Y') }} TirtaX Logistik. All rights reserved.
            </p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto focus on email field
    document.addEventListener('DOMContentLoaded', function() {
        const emailInput = document.getElementById('email');
        if (emailInput && !emailInput.value) {
            emailInput.focus();
        }
    });
</script>
@endpush
@endsection
