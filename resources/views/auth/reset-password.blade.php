@extends('layouts.app')

@section('title', 'Reset Password - TirtaX')

@section('content')
    <div class="min-h-[80vh] flex items-center justify-center px-4 py-12">
        <div class="max-w-md w-full">
            <div class="bg-white rounded-2xl shadow-xl border border-sky-100 p-8">
                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-sky-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-navy-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-navy-950 mb-2">Reset Password</h1>
                    <p class="text-gray-600">Masukkan password baru Anda.</p>
                </div>

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

                <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div>
                        <label class="block text-sm font-medium text-navy-900 mb-2">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900 placeholder-gray-400 bg-white"
                            placeholder="email@example.com">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-navy-900 mb-2">Password Baru</label>
                        <input type="password" name="password" required
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900 placeholder-gray-400 bg-white"
                            placeholder="Minimal 8 karakter">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-navy-900 mb-2">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-navy-700 focus:border-navy-700 p-3 border text-gray-900 placeholder-gray-400 bg-white"
                            placeholder="Ulangi password baru">
                    </div>

                    <button type="submit"
                        class="w-full py-3 bg-navy-800 text-white font-bold rounded-lg hover:bg-navy-900 transition shadow-lg">
                        Reset Password
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection