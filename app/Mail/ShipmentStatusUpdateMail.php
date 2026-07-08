<?php

namespace App\Mail;

use App\Models\Shipment;
use App\Models\ShipmentHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ShipmentStatusUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $shipment;
    public $history;
    public $statusTitle;
    public $statusMessage;

    public function __construct(Shipment $shipment, ShipmentHistory $history)
    {
        $this->shipment = $shipment;
        $this->history = $history;

        // Set judul dan pesan berdasarkan status
        $statusInfo = $this->getStatusInfo($history->status);
        $this->statusTitle = $statusInfo['title'];
        $this->statusMessage = $statusInfo['message'];
    }

    private function getStatusInfo($status)
    {
        $info = [
            'picked_up' => [
                'title' => 'Paket Anda Sudah Diambil Kurir',
                'message' => 'Kurir kami telah mengambil paket Anda dan akan segera mengantarkannya ke tujuan.',
            ],
            'in_transit' => [
                'title' => 'Paket Anda Sedang Dalam Perjalanan',
                'message' => 'Paket Anda sedang dalam perjalanan menuju kota tujuan. Mohon bersabar, paket akan segera sampai.',
            ],
            'delivered' => [
                'title' => 'Paket Anda Telah Sampai Tujuan! 🎉',
                'message' => 'Selamat! Paket Anda telah berhasil diterima oleh penerima. Terima kasih telah menggunakan layanan TirtaX.',
            ],
        ];

        return $info[$status] ?? [
            'title' => 'Status Pengiriman Diperbarui',
            'message' => 'Status pengiriman Anda telah diperbarui.',
        ];
    }

    public function build()
    {
        return $this
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject($this->statusTitle . ' - ' . $this->shipment->tracking_number)
            ->markdown('emails.shipment-status-update');
    }
}