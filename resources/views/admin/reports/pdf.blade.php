<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Pengiriman TirtaX</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #1e40af;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #1e40af;
            margin: 0;
            font-size: 24px;
        }

        .header p {
            color: #666;
            margin: 5px 0 0 0;
        }

        .stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .stat-box {
            flex: 1;
            padding: 15px;
            margin: 0 10px;
            background: #f0f4ff;
            border-radius: 8px;
            text-align: center;
        }

        .stat-box:first-child {
            margin-left: 0;
        }

        .stat-box:last-child {
            margin-right: 0;
        }

        .stat-label {
            font-size: 11px;
            color: #666;
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 18px;
            font-weight: bold;
            color: #1e40af;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background: #1e40af;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 11px;
        }

        td {
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>TirtaX Logistik</h1>
        <p>Laporan Pengiriman</p>
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} -
            {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
    </div>

    <div class="stats">
        <div class="stat-box">
            <div class="stat-label">Total Pengiriman</div>
            <div class="stat-value">{{ number_format($totalShipments) }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Total Revenue</div>
            <div class="stat-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
        </div>
    </div>

    <h3>Status Pengiriman</h3>
    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($statusBreakdown as $status)
                <tr>
                    <td>{{ ucfirst(str_replace('_', ' ', $status->status)) }}</td>
                    <td>{{ $status->count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3 style="margin-top: 30px;">Daftar Pengiriman</h3>
    <table>
        <thead>
            <tr>
                <th>No. Resi</th>
                <th>Customer</th>
                <th>Tujuan</th>
                <th>Biaya</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($shipments as $shipment)
                <tr>
                    <td>{{ $shipment->tracking_number }}</td>
                    <td>{{ $shipment->user->name ?? '-' }}</td>
                    <td>{{ $shipment->destination_city }}</td>
                    <td>Rp {{ number_format($shipment->total_cost, 0, ',', '.') }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $shipment->status)) }}</td>
                    <td>{{ $shipment->created_at->format('d M Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d M Y, H:i') }} WIB</p>
        <p>&copy; {{ date('Y') }} TirtaX Logistik. All rights reserved.</p>
    </div>
</body>

</html>