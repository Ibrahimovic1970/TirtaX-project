<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $shipment->tracking_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 50px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            border-radius: 12px;
        }

        .action-buttons {
            display: flex;
            gap: 12px;
            margin-bottom: 30px;
            justify-content: flex-end;
            align-items: center;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-back {
            background: #6b7280;
            color: white;
        }

        .btn-back:hover {
            background: #4b5563;
        }

        .btn-print {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-print:hover {
            background: linear-gradient(135deg, #5568d3 0%, #6a3f8f 100%);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #667eea;
            padding-bottom: 25px;
            margin-bottom: 35px;
        }

        .logo {
            font-size: 36px;
            font-weight: bold;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .invoice-title {
            text-align: right;
        }

        .invoice-title h1 {
            font-size: 32px;
            color: #1f2937;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .invoice-title p {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 3px;
        }

        .info-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-bottom: 35px;
        }

        .info-box {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            padding: 25px;
            border-radius: 12px;
            border-left: 5px solid #667eea;
        }

        .info-box h3 {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .info-box p {
            font-size: 15px;
            color: #1f2937;
            margin-bottom: 6px;
            line-height: 1.6;
        }

        .info-box strong {
            color: #111827;
            font-weight: 600;
        }

        .section-title {
            font-size: 20px;
            color: #1f2937;
            margin-bottom: 20px;
            font-weight: 700;
            padding-bottom: 12px;
            border-bottom: 2px solid #e5e7eb;
        }

        .shipment-details,
        .cost-summary,
        .payment-info {
            background: #f9fafb;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #6b7280;
            font-size: 14px;
            font-weight: 500;
        }

        .detail-value {
            color: #1f2937;
            font-weight: 600;
            font-size: 14px;
        }

        .cost-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 15px;
        }

        .cost-row.total {
            border-top: 3px solid #667eea;
            margin-top: 15px;
            padding-top: 20px;
            font-size: 20px;
            font-weight: 700;
            color: #667eea;
        }

        .status-badge {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            margin-top: 10px;
        }

        .status-paid {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .footer {
            text-align: center;
            margin-top: 50px;
            padding-top: 25px;
            border-top: 2px solid #e5e7eb;
            color: #6b7280;
            font-size: 13px;
            line-height: 1.8;
        }

        .footer strong {
            color: #1f2937;
            font-size: 15px;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .action-buttons {
                display: none !important;
            }

            .invoice-container {
                box-shadow: none;
                padding: 30px;
                border-radius: 0;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .invoice-container {
                padding: 25px;
            }

            .action-buttons {
                flex-direction: column;
                align-items: stretch;
            }

            .btn {
                justify-content: center;
            }

            .info-section {
                grid-template-columns: 1fr;
            }

            .header {
                flex-direction: column;
                text-align: center;
                gap: 20px;
            }

            .invoice-title {
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <div class="action-buttons">
        <a href="{{ route('shipments.detail', $shipment->id) }}" class="btn btn-back">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            Kembali
        </a>
        <button onclick="window.print()" class="btn btn-print">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                </path>
            </svg>
            Cetak / Download PDF
        </button>
    </div>

    <div class="invoice-container">
        {{-- Header --}}
        <div class="header">
            <div class="logo">
                Tirta<span style="color: #764ba2;">X</span>
            </div>
            <div class="invoice-title">
                <h1>INVOICE</h1>
                <p><strong>No:</strong> {{ $shipment->tracking_number }}</p>
                <p><strong>Tanggal:</strong> {{ $shipment->created_at->format('d M Y') }}</p>
            </div>
        </div>

        {{-- Info Section --}}
        <div class="info-section">
            <div class="info-box">
                <h3>👤 DARI (PENGIRIM)</h3>
                <p><strong>{{ $shipment->user->name }}</strong></p>
                <p>📧 {{ $shipment->user->email }}</p>
                <p>📍 Kota Asal: {{ $shipment->origin_city }}</p>
            </div>

            <div class="info-box">
                <h3>🏠 KEPADA (PENERIMA)</h3>
                <p><strong>{{ $shipment->receiver_name }}</strong></p>
                <p>📱 {{ $shipment->receiver_phone }}</p>
                <p>{{ $shipment->receiver_address }}</p>
                <p>📍 Kota Tujuan: {{ $shipment->destination_city }}</p>
            </div>
        </div>

        {{-- Shipment Details --}}
        <div class="shipment-details">
            <h3 class="section-title">📦 Detail Pengiriman</h3>
            <div class="detail-row">
                <span class="detail-label">Nomor Resi</span>
                <span class="detail-value">{{ $shipment->tracking_number }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Berat Paket</span>
                <span class="detail-value">{{ $shipment->weight }} Kg</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Kurir</span>
                <span class="detail-value">TirtaX Express</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Layanan</span>
                <span class="detail-value">Reguler</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Tanggal Kirim</span>
                <span class="detail-value">{{ $shipment->created_at->format('d M Y, H:i') }} WIB</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status</span>
                <span class="detail-value">
                    @php
                        $statusLabels = [
                            'paid' => 'Sudah Dibayar',
                            'picked_up' => 'Diambil Kurir',
                            'in_transit' => 'Dalam Perjalanan',
                            'delivered' => 'Terkirim',
                        ];
                        $label = $statusLabels[$shipment->status] ?? $shipment->status;
                    @endphp
                    {{ $label }}
                </span>
            </div>
        </div>

        {{-- Cost Summary --}}
        <div class="cost-summary">
            <h3 class="section-title">💰 Rincian Biaya</h3>
            <div class="cost-row">
                <span>Ongkos Kirim ({{ $shipment->weight }} Kg × Rp 10.000)</span>
                <span>Rp {{ number_format($shipment->total_cost, 0, ',', '.') }}</span>
            </div>
            <div class="cost-row">
                <span>Asuransi</span>
                <span>Rp 0</span>
            </div>
            <div class="cost-row">
                <span>Biaya Admin</span>
                <span>Rp 0</span>
            </div>
            <div class="cost-row total">
                <span>TOTAL</span>
                <span>Rp {{ number_format($shipment->total_cost, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Payment Info --}}
        <div class="payment-info">
            <h3 class="section-title">💳 Informasi Pembayaran</h3>
            <div class="detail-row">
                <span class="detail-label">Metode Pembayaran</span>
                <span class="detail-value">Midtrans Payment Gateway</span>
            </div>
            @php
                $paidHistory = $shipment->histories()->where('status', 'paid')->first();
            @endphp
            @if($paidHistory)
                <div class="detail-row">
                    <span class="detail-label">Tanggal Bayar</span>
                    <span class="detail-value">{{ $paidHistory->created_at->format('d M Y, H:i') }} WIB</span>
                </div>
            @endif
            <div class="detail-row">
                <span class="detail-label">Status Pembayaran</span>
                <span class="detail-value">
                    <span class="status-badge status-paid">✅ LUNAS</span>
                </span>
            </div>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <p><strong>Terima kasih telah menggunakan layanan TirtaX Logistik</strong></p>
            <p>📧 support@tirtax.com | ☎️ 0800-123-4567</p>
            <p style="margin-top: 15px; font-size: 12px;">
                Invoice ini digenerate secara otomatis pada {{ now()->format('d M Y, H:i') }} WIB
            </p>
        </div>
    </div>
</body>

</html>