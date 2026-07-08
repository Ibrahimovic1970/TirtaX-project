@extends('layouts.app')

@section('title', 'Lupa Password - TirtaX')

@section('content')
    <div class="min-h-[80vh] flex items-center justify-center px-4 py-12">
        <div class="max-w-md w-full">
            <div class="bg-white rounded-2xl shadow-xl border border-sky-100 p-8">
                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-sky-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-navy-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                            </path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-navy-950 mb-2">Lupa Password?</h1>
                    <p class="text-gray-600">Masukkan email Anda untuk menerima link reset password.</p>
                </div>

                {{-- Success Message --}}
                @if(session('status'))
                    <div class="bg-green-50 border-l-4 border-green-500 rounded-r-xl p-4 mb-6">
                        <p class="text-green-700 font-medium">{{ session('status') }}</p>
                    </div>
                @endif

                {{-- Error Messages --}}
                @if($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 rounded-r-xl p-4 mb-6">
                        <ul class="text-red-700 text-sm list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-navy-900 mb-2">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900 placeholder-gray-400 bg-white"
                            placeholder="email@example.com">
                    </div>

                    <button type="submit"
                        class="w-full py-3 bg-navy-800 text-white font-bold rounded-lg hover:bg-navy-900 transition shadow-lg">
                        Kirim Link Reset Password
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="text-navy-700 hover:text-navy-900 font-semibold text-sm">
                        ← Kembali ke Halaman Login
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection