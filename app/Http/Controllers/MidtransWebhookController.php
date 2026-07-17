<?php
namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    /**
     * Handle Midtrans notification webhook
     */
    public function handle(Request $request)
    {
        // Setup Midtrans configuration
        \Midtrans\Config::$serverKey    = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized  = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds        = config('midtrans.is_3ds');

        try {
            // Get notification data
            $notification = $request->all();

            // Log the notification for debugging
            Log::info('Midtrans Notification Received', $notification);

            // Get order_id from notification
            $orderId = $notification['order_id'] ?? '';

            // Extract base order_id (remove timestamp and hash suffix)
            $baseOrderId = $this->extractBaseOrderId($orderId);

            // Find shipment by base order_id (tracking_number)
            $shipment = Shipment::where('tracking_number', $baseOrderId)->first();

            if (! $shipment) {
                Log::error('Shipment not found for order_id: ' . $orderId);
                return response()->json(['status' => 'error', 'message' => 'Shipment not found'], 404);
            }

            // Update shipment status based on transaction status
            $transactionStatus = $notification['transaction_status'] ?? '';
            $fraudStatus       = $notification['fraud_status'] ?? '';

            Log::info('Processing payment for order_id: ' . $orderId, [
                'transaction_status' => $transactionStatus,
                'fraud_status'       => $fraudStatus,
            ]);

            $newStatus     = $shipment->status;
            $statusChanged = false;

            switch ($transactionStatus) {
                case 'capture':
                    if ($fraudStatus === 'accept') {
                        $newStatus     = 'paid';
                        $statusChanged = true;
                    }
                    break;

                case 'settlement':
                    $newStatus     = 'paid';
                    $statusChanged = true;
                    break;

                case 'pending':
                    $newStatus     = 'pending';
                    $statusChanged = true;
                    break;

                case 'deny':
                case 'expire':
                case 'cancel':
                    $newStatus     = 'created';
                    $statusChanged = true;
                    break;
            }

            // Update shipment status if changed
            if ($statusChanged && $shipment->status !== $newStatus) {
                $shipment->update([
                    'status' => $newStatus,
                ]);

                Log::info('Shipment status updated', [
                    'order_id'   => $orderId,
                    'old_status' => $shipment->status,
                    'new_status' => $newStatus,
                ]);
            }

            return response()->json(['status' => 'success', 'message' => 'Webhook processed successfully']);

        } catch (\Exception $e) {
            Log::error('Midtrans Webhook Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Extract base order_id from unique order_id
     * Format: TRX-20260717-C9RZQN-1721234567-abc123 -> TRX-20260717-C9RZQN
     */
    private function extractBaseOrderId($orderId)
    {
        // Split by dash
        $parts = explode('-', $orderId);

        // If we have at least 3 parts, reconstruct the base order_id
        if (count($parts) >= 3) {
            // Take first 3 parts: TRX-20260717-C9RZQN
            return $parts[0] . '-' . $parts[1] . '-' . $parts[2];
        }

        return $orderId;
    }
}
