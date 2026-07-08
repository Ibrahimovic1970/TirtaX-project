@extends('layouts.app')
@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-xl shadow-lg">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-tirtax-900">Masuk ke TirtaX</h2>
                <p class="mt-2 text-sm text-gray-600">Atau <a href="{{ route('register') }}"
                        class="font-medium text-tirtax-600 hover:text-tirtax-500">daftar akun baru</a></p>
            </div>
            <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="rounded-md shadow-sm space-y-4">
                    <div>
                        <label class="sr-only">Email</label>
                        <input name="email" type="email" required
                            class="appearance-none rounded-lg relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-tirtax-500 focus:border-tirtax-500 focus:z-10 sm:text-sm"
                            placeholder="Alamat Email">
                    </div>
                    <div>
                        <label class="sr-only">Password</label>
                        <input name="password" type="password" required
                            class="appearance-none rounded-lg relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-tirtax-500 focus:border-tirtax-500 focus:z-10 sm:text-sm"
                            placeholder="Password">
                    </div>
                </div>
                @if ($errors->any())
                    <div class="text-red-500 text-sm text-center">{{ $errors->first('email') }}</div>
                @endif
                <button type="submit"
                    class="w-full py-3 bg-navy-800 text-white font-bold rounded-lg hover:bg-navy-900 transition shadow-lg">
                    Masuk
                </button>
                <div class="text-center mt-4">
                    <a href="{{ route('password.request') }}"
                        class="text-sm text-navy-700 hover:text-navy-900 font-semibold">
                        Lupa Password?
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection