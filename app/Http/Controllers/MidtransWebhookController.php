<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\ShipmentHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('Webhook received - Midtrans');

        try {
            \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
            \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $notif = new \Midtrans\Notification();

            $order_id = $notif->order_id;
            $transaction_status = $notif->transaction_status;
            $fraud_status = $notif->fraud_status;

            Log::info('Webhook data', [
                'order_id' => $order_id,
                'status' => $transaction_status,
                'fraud' => $fraud_status
            ]);

            // FIX: Ambil tracking_number dari custom_field1 jika order_id mengandung timestamp
            // order_id format: TRX-20260610-VKNSGI-1718000000
            // Kita perlu extract tracking_number aslinya
            $trackingNumber = $this->extractTrackingNumber($order_id);

            // Cari shipment berdasarkan tracking_number
            $shipment = Shipment::where('tracking_number', $trackingNumber)->first();

            if (!$shipment) {
                Log::warning('Shipment not found for order_id: ' . $order_id);
                return response()->json(['message' => 'Shipment not found'], 404);
            }

            $newStatus = null;
            $description = '';

            // Logic status berdasarkan response Midtrans
            if ($transaction_status == 'capture') {
                if ($fraud_status == 'accept') {
                    $newStatus = 'paid';
                    $description = 'Pembayaran berhasil diverifikasi (Midtrans)';
                }
            } elseif ($transaction_status == 'settlement') {
                $newStatus = 'paid';
                $description = 'Pembayaran berhasil (Midtrans)';
            } elseif ($transaction_status == 'pending') {
                $description = 'Menunggu pembayaran (Midtrans)';
            } elseif (in_array($transaction_status, ['deny', 'cancel', 'expire'])) {
                // Jangan ubah status ke cancelled, biarkan user retry
                $description = 'Pembayaran ' . $transaction_status . ' - User dapat retry';
            }

            // Update jika ada perubahan status
            if ($newStatus && $shipment->status !== $newStatus) {
                $shipment->update(['status' => $newStatus]);

                ShipmentHistory::create([
                    'shipment_id' => $shipment->id,
                    'status' => $newStatus,
                    'description' => $description,
                    'location' => 'Payment Gateway (Midtrans)',
                ]);

                Log::info('Shipment status updated', [
                    'tracking_number' => $trackingNumber,
                    'new_status' => $newStatus
                ]);
            }

            return response()->json(['message' => 'Success'], 200);

        } catch (\Exception $e) {
            Log::error('Webhook error: ' . $e->getMessage());
            return response()->json(['message' => 'Error'], 500);
        }
    }

    /**
     * Extract tracking_number dari order_id yang mengandung timestamp
     * Input: TRX-20260610-VKNSGI-1718000000
     * Output: TRX-20260610-VKNSGI
     */
    private function extractTrackingNumber($orderId)
    {
        // Format tracking_number: TRX-YYYYMMDD-XXXXXX (19 karakter)
        // Format order_id: TRX-YYYYMMDD-XXXXXX-timestamp
        // Kita ambil 19 karakter pertama jika mengandung timestamp

        $parts = explode('-', $orderId);

        // Jika ada lebih dari 3 bagian (ada timestamp di akhir)
        if (count($parts) > 3) {
            // Ambil 3 bagian pertama sebagai tracking_number
            return $parts[0] . '-' . $parts[1] . '-' . $parts[2];
        }

        // Jika tidak ada timestamp, return as-is
        return $orderId;
    }
}