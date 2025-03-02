@extends('admin.layouts.halaman')

@section('title', 'Riwayat Pemesanan')
@section('header-title', 'Riwayat Pemesanan')

@push('head')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <!-- Sertakan meta CSRF token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <div class="header-title">
            <h4 class="card-title">Riwayat Pemesanan</h4>
        </div>
    </div>
    <div class="card-body" style="min-height: 400px">
        <table id="riwayatTable" class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Pemesan</th>
                    <th>Tujuan</th>
                    <th>Jadwal Tiket</th>
                    <th>Status Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
@endsection

    <!-- jQuery, DataTables, Moment, SweetAlert2 & Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function(){
            let token = localStorage.getItem('token');
            if (!token) {
                alert("Silakan login terlebih dahulu!");
                window.location.href = "/login";
                return;
            }
            
            // Inisialisasi DataTable menggunakan API riwayat pemesanan
            $('#riwayatTable').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: '/api/riwayat-pemesanan',
                    type: 'GET',
                    dataSrc: '', // API mengembalikan array data
                    headers: { "Authorization": "Bearer " + token }
                },
                columns: [
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        // Nama pemesan dari relasi user
                        data: 'user.name',
                        defaultContent: '-'
                    },
                    {
                        // Tujuan dari relasi jadwal_tiket
                        data: 'jadwal_tiket.tujuan',
                        defaultContent: '-'
                    },
                    {
                        // Gabungkan tanggal dan waktu keberangkatan dari relasi jadwal_tiket
                        data: 'jadwal_tiket',
                        render: function(data, type, row) {
                            let tanggal = data.tanggal_keberangkatan ? moment(data.tanggal_keberangkatan).format('DD MMMM YYYY') : '-';
                            let waktu = data.waktu_keberangkatan ? moment(data.waktu_keberangkatan, 'HH:mm:ss').format('HH:mm') + ' WIB' : '-';
                            return tanggal + ' ' + waktu;
                        }
                    },
                    {
                        // Tampilkan status pembayaran; jika null tampilkan "Belum Lunas"
                        data: 'pembayaran',
                        render: function(data, type, row) {
                            return data ? data.status_pembayaran : 'Belum Lunas';
                        }
                    },
                    {
                        // Jika status pembayaran sudah lunas tampilkan tombol "Cetak Invoice"
                        data: 'id',
                        render: function(data, type, row) {
                            if (row.pembayaran && row.pembayaran.status_pembayaran.toLowerCase() === 'lunas') {
                                return `<a href="/invoice/${data}" class="btn btn-success btn-sm" target="_blank">Cetak Invoice</a>`;
                            }
                            return '';
                        }
                    }
                ],
                language: {
                    emptyTable: "<div style='text-align: center; font-weight: medium; font-size: 16px;'>Tidak ada data tersedia</div>"
                }
            });
        });
    </script>
