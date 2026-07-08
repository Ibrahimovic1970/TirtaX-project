<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offline - TirtaX</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
</head>

<body
    class="bg-gradient-to-br from-navy-950 via-navy-900 to-navy-800 min-h-screen flex items-center justify-center px-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl p-8 text-center">
        {{-- Offline Icon --}}
        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M18.364 5.636a9 9 0 010 12.728m0 0l-2.829-2.829m2.829 2.829L21 21M15.536 8.464a5 5 0 010 7.072m0 0l-2.829-2.829m-4.243 2.829a4.978 4.978 0 01-1.414-2.83m-1.414 5.658a9 9 0 01-2.167-9.238m7.824 2.167a1 1 0 111.414 1.414m-1.414-1.414L3 3m8.293 8.293l1.414 1.414">
                </path>
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-navy-950 mb-2">Anda Offline</h1>
        <p class="text-gray-600 mb-6">
            Maaf, Anda tidak terhubung ke internet. Beberapa fitur mungkin tidak tersedia.
        </p>

        <div class="bg-sky-50 rounded-xl p-4 mb-6 border border-sky-100">
            <p class="text-sm text-sky-900">
                <strong>Tips:</strong> Anda masih bisa melihat data yang sudah di-cache sebelumnya.
            </p>
        </div>

        <button onclick="window.location.reload()"
            class="w-full py-3 bg-navy-800 text-white font-bold rounded-lg hover:bg-navy-900 transition shadow-lg flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                </path>
            </svg>
            Coba Lagi
        </button>

        <a href="/" class="inline-block mt-4 text-navy-700 hover:text-navy-900 font-semibold text-sm">
            Kembali ke Beranda
        </a>
    </div>
</body>

</html>