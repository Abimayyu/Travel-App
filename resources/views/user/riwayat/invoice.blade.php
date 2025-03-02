@extends('admin.layouts.halaman')

@section('title', 'Invoice')
@section('header-title', 'Invoice Pemesanan')

@push('head')
    <style>
        .invoice-container {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            padding: 30px;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .invoice-header {
            border-bottom: 2px solid #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        .invoice-header h2 {
            margin: 0;
            font-weight: bold;
        }
        .invoice-header p {
            margin: 5px 0 0;
        }
        .invoice-details h4 {
            margin-bottom: 15px;
            font-size: 18px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .invoice-details p {
            margin: 5px 0;
        }
        .invoice-details table {
            margin-top: 15px;
        }
        .invoice-footer {
            text-align: right;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            font-size: 0.9em;
            color: #777;
            margin-top: 20px;
        }
    </style>
@endpush

@section('content')
<div class="invoice-container">
    <div class="invoice-header text-center">
        <h2>Travel App Invoice</h2>
        <p>Invoice #{{ $pemesanan->id }}</p>
    </div>

    <div class="invoice-details">
        <h4>Detail Pemesanan</h4>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Nama Pemesan:</strong> {{ $pemesanan->user->name }}</p>
                <p>
                    <strong>Status Pembayaran:</strong> 
                    @if($pemesanan->pembayaran)
                        <span class="badge bg-success">{{ ucfirst($pemesanan->pembayaran->status_pembayaran) }}</span>
                    @else
                        <span class="badge bg-warning">Belum Lunas</span>
                    @endif
                </p>
            </div>
            <div class="col-md-6 text-end">
                <p><strong>Tanggal Invoice:</strong> {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
            </div>
        </div>

        <h4>Detail Tiket</h4>
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th>Tujuan</th>
                    <td>{{ $pemesanan->jadwal_tiket->tujuan }}</td>
                </tr>
                <tr>
                    <th>Tanggal Keberangkatan</th>
                    <td>{{ \Carbon\Carbon::parse($pemesanan->jadwal_tiket->tanggal_keberangkatan)->format('d F Y') }}</td>
                </tr>
                <tr>
                    <th>Waktu Keberangkatan</th>
                    <td>{{ \Carbon\Carbon::parse($pemesanan->jadwal_tiket->waktu_keberangkatan)->format('H:i') }} WIB</td>
                </tr>
                <tr>
                    <th>Harga Tiket</th>
                    <td>Rp {{ number_format($pemesanan->jadwal_tiket->harga_tiket, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="invoice-footer">
        <p>Terima kasih telah memesan tiket melalui Travel App!</p>
    </div>
</div>
@endsection
