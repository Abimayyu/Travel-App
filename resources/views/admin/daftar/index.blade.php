@extends('admin.layouts.halaman')

@section('title', 'Daftar Pesanan')
@section('header-title', 'Daftar Pesanan')

@push('head')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <div class="header-title">
            <h4 class="card-title">Daftar Pesanan</h4>
        </div>
    </div>
    <div class="card-body" style="min-height: 400px">
        <table id="pesananTable" class="table table-striped table-hover">
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
            
            // Inisialisasi DataTable dengan API daftar pesanan
            $('#pesananTable').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: '/api/daftar',
                    type: 'GET',
                    dataSrc: '', 
                    headers: { "Authorization": "Bearer " + token }
                },
                columns: [
                    {
                        data: null,
                        render: (data, type, row, meta) => meta.row + 1
                    },
                    {
                        // Ambil nama user dari relasi user
                        data: 'user.name',
                        defaultContent: '-'
                    },
                    {
                        // Ambil tujuan dari relasi jadwal_tiket
                        data: 'jadwal_tiket.tujuan',
                        defaultContent: '-'
                    },
                    {
                        // Gabungkan tanggal dan waktu keberangkatan
                        data: 'jadwal_tiket',
                        render: function(data, type, row) {
                            let tanggal = data.tanggal_keberangkatan ? moment(data.tanggal_keberangkatan).format('DD MMMM YYYY') : '-';
                            let waktu = data.waktu_keberangkatan ? moment(data.waktu_keberangkatan, 'HH:mm:ss').format('HH:mm') + ' WIB' : '-';
                            return tanggal + ' ' + waktu;
                        }
                    },
                    {
                        // Tampilkan status pembayaran jika ada, jika tidak tampilkan "Belum Lunas"
                        data: 'pembayaran',
                        render: function(data, type, row) {
                            return data ? data.status_pembayaran : 'Belum Lunas';
                        }
                    },
                    {
                        // Tampilkan tombol aksi jika status pembayaran belum ada (null)
                        data: 'id',
                        render: function(data, type, row) {
                            if(row.pembayaran == null) {
                                return `<button class="btn btn-primary btn-sm" onclick="updateStatusLunas(${data})">Update Status Lunas</button>`;
                            }
                            return `<span class="badge bg-success">Lunas</span>`;
                        }
                    }
                ],
                language: {
                    emptyTable: "<div style='text-align: center; font-weight: medium; font-size: 16px;'>Tidak ada data tersedia</div>"
                }
            });
        });

        // Fungsi untuk update status pembayaran menjadi lunas
        function updateStatusLunas(pemesananId) {
            let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            let token = localStorage.getItem('token');
            $.ajax({
                url: `/api/update-status-lunas/${pemesananId}`,
                type: 'POST',
                headers: { 
                    "Authorization": "Bearer " + token,
                    "X-CSRF-TOKEN": csrfToken,
                    "Accept": "application/json"
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Status Diperbarui',
                        text: 'Pembayaran telah diupdate menjadi lunas'
                    });
                    $('#pesananTable').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    Swal.fire('Error', 'Gagal memperbarui status pembayaran.', 'error');
                }
            });
        }
    </script>
