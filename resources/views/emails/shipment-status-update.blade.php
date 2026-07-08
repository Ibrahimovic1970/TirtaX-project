@component('mail::message')
# {{ $statusTitle }}

Halo {{ $shipment->user->name }},

{{ $statusMessage }}

@component('mail::panel')
**📦 Detail Pengiriman**

**Nomor Resi:** {{ $shipment->tracking_number }}

**Penerima:** {{ $shipment->receiver_name }}

**Tujuan:** {{ $shipment->destination_city }}

**Status Terbaru:** {{ ucfirst(str_replace('_', ' ', $history->status)) }}

**Keterangan:** {{ $history->description }}

**Lokasi:** {{ $history->location }}

**Waktu:** {{ $history->created_at->format('d M Y, H:i') }} WIB
@endcomponent

@if($history->photo_url)
    @component('mail::panel')
    ** Foto Bukti Pengiriman**

    Paket Anda telah diterima dengan baik. Berikut foto bukti pengiriman:

    @component('mail::image', ['url' => url($history->photo_url)])
    Bukti Pengiriman
    @endcomponent

    *Foto ini diambil saat paket diterima oleh penerima.*
    @endcomponent
@endif

Anda dapat melacak pergerakan paket Anda secara real-time melalui website kami.

@component('mail::button', ['url' => url('/track/' . $shipment->tracking_number), 'color' => 'success'])
Lacak Paket Saya
@endcomponent

Jika Anda memiliki pertanyaan atau kendala, silakan balas email ini atau hubungi customer service kami.

Terima kasih telah mempercayai **TirtaX Logistik**.

Salam,
Tim TirtaX
@endcomponent