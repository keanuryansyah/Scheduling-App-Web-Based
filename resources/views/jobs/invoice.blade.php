<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $job->job_title }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #1f2937;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #4f46e5;
            padding-bottom: 14px;
            margin-bottom: 24px;
        }

        .brand img {
            width: 120px;
            margin-bottom: 6px;
        }

        .brand-title {
            font-size: 18px;
            font-weight: bold;
            color: #4f46e5;
            letter-spacing: 1px;
        }

        .invoice-info {
            text-align: right;
        }

        .label {
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
        }

        .value {
            font-weight: bold;
            margin-bottom: 6px;
        }

        .box {
            border: 1px solid #e5e7eb;
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 14px;
        }

        .box-title {
            font-size: 11px;
            font-weight: bold;
            color: #4f46e5;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        table th {
            background: #f3f4f6;
            font-size: 11px;
            text-transform: uppercase;
        }

        table th,
        table td {
            border: 1px solid #e5e7eb;
            padding: 8px;
        }

        .total-box {
            background: #f9fafb;
            border: 2px solid #4f46e5;
            padding: 16px;
            border-radius: 10px;
            margin-top: 24px;
            text-align: right;
        }

        .total-label {
            font-size: 12px;
            color: #6b7280;
        }

        .total-value {
            font-size: 20px;
            font-weight: bold;
            color: #4f46e5;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <div class="header">
        <div class="brand">
            <img src="{{ public_path('images/logo-izz.png') }}">
            <div class="brand-title">INVOICE</div>
        </div>

        <div class="invoice-info">
            <div class="label">Invoice No</div>
            <div class="value">INV-{{ $job->id }}</div>

            <div class="label">Tanggal Invoice Dibuat</div>
            <div class="value">{{ now()->translatedFormat('d F Y') }}
            </div>
        </div>
    </div>

    <!-- CLIENT & JOB INFO -->
    <div class="box">
        <div class="box-title">Informasi Klien</div>
        <div class="value">KLIEN: {{ $job->client_name }}</div>
        <div>NO HP: {{ $job->client_phone }}</div>
    </div>

    <div class="box">
        <div class="box-title">Detail Pekerjaan</div>
        <div class="value text-uppercase">{{ $job->job_title }}</div>
        <div>Jenis Pekerjaan: {{ $job->type->job_type_name ?? '-' }}</div>
        <div>Tanggal:
            {{ $job->job_date->translatedFormat('d F Y') }}
        </div>
        <div>Jam: {{ $job->start_time }} - {{ $job->end_time }}</div>
        <div>Lokasi: {{ $job->location }}</div>
    </div>

    <!-- CREW -->
    <!-- <div class="box">
        <div class="box-title">Tim Bertugas</div>

        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Tugas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($job->assignments as $assign)
                <tr>
                    <td>{{ $assign->user->name }}</td>
                    <td>{{ ucfirst($assign->task ?? 'Crew') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div> -->

    <!-- TOTAL -->
    <div class="total-box">
        <div class="total-label">Total Tagihan</div>
        @php
        $amount = session('invoice_amount') ?? $job->amount;
        @endphp

        <div class="total-value">
            Rp {{ number_format($amount, 0, ',', '.') }}
        </div>

        <div class="label">
            Status Pembayaran: (unpaid)
        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        Invoice ini dibuat secara otomatis oleh sistem Izzatishot Creative.<br>
        Terima kasih telah mempercayakan momen Anda kepada kami.
    </div>

</body>

</html>