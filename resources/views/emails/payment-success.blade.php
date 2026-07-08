@component('mail::message')
# Halo, {{ $user->name }}!

Pembayaran untuk pengiriman Anda telah **berhasil kami terima**. Paket Anda akan segera diproses oleh tim logistik
TirtaX.

@component('mail::panel')
**📦 Detail Pengiriman**

**Nomor Resi:** {{ $shipment->tracking_number }}
**Tujuan:** {{ $shipment->destination_city }}
**Penerima:** {{ $shipment->receiver_name }}
**Total Dibayar:** Rp {{ number_format($shipment->total_cost, 0, ',', '.') }}
@endcomponent

Anda dapat melacak pergerakan paket Anda secara real-time kapan saja melalui website kami.

@component('mail::button', ['url' => url('/track/' . $shipment->tracking_number), 'color' => 'success'])
Lacak Paket Saya
@endcomponent

Jika Anda memiliki pertanyaan atau kendala, silakan balas email ini atau hubungi customer service kami.

Terima kasih telah mempercayai **TirtaX Logistik**.

Salam,
Tim TirtaX
@endcomponent