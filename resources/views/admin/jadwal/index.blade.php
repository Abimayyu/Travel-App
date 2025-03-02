@extends('admin.layouts.halaman')

@section('title', 'Jadwal')
@section('header-title', 'Kelola Jadwal')

@push('head')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endpush

@php
    // Pastikan user sudah di-auth dan properti role tersedia
    $role = Auth::user()->role;
@endphp

@section('content')
<div class="card">
    <div class="card-header">
        <div class="header-title">
            <h4 class="card-title">Semua Jadwal</h4>
        </div>
    </div>
    <div class="card-body" style="min-height: 400px">
        @if($role !== 'pemesan')
            <a href="{{ url('/jadwal/create') }}" class="btn btn-primary mb-3">Tambah Jadwal</a>
        @endif

        <table id="jadwalTable" class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tujuan</th>
                    <th>Tanggal</th>
                    <th>Waktu</th>
                    <th>Kuota</th>
                    <th>Tersedia</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- Modal untuk menampilkan data pemesan (hanya untuk admin) -->
<div class="modal fade" id="pemesanModal" tabindex="-1" aria-labelledby="pemesanModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
       <div class="modal-header">
          <h5 class="modal-title" id="pemesanModalLabel">Data Pemesan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
       </div>
       <div class="modal-body">
           <table class="table table-bordered" id="pemesanTable">
              <thead>
                <tr>
                   <th>No</th>
                   <th>Nama Pemesan</th>
                   <th>Status Pembayaran</th>
                </tr>
              </thead>
              <tbody></tbody>
           </table>
       </div>
    </div>
  </div>
</div>

<!-- jQuery, DataTables, Moment, SweetAlert2 & Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/locale/id.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Pastikan Bootstrap JS sudah terinclude -->

<script>
    $(document).ready(function() {
        // Set role user dari Blade ke variabel JS
        window.userRole = '{{ $role }}';
        let token = localStorage.getItem('token');

        if (!token) {
            alert("Silakan login terlebih dahulu!");
            window.location.href = "/login";
            return;
        }

        let table = $('#jadwalTable').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: '/api/jadwal',
                type: 'GET',
                dataSrc: '',
                headers: { "Authorization": "Bearer " + token }
            },
            columns: [
                { data: null, render: (data, type, row, meta) => meta.row + 1 },
                { data: 'tujuan' },
                { data: 'tanggal_keberangkatan', render: data => moment(data).format('DD MMMM YYYY') },
                { data: 'waktu_keberangkatan', render: data => moment(data, 'HH:mm:ss').format('HH:mm') + ' WIB' },
                { data: 'kuota' },
                { 
                    data: null, 
                    render: (data, type, row) => {
                        // Asumsikan API mengembalikan properti jumlah_penumpang
                        let jumlahPenumpang = row.jumlah_penumpang || 0;
                        return row.kuota - jumlahPenumpang;
                    }
                },
                { data: 'harga_tiket', render: data => 'Rp ' + new Intl.NumberFormat('id-ID').format(data) },
                {
                    data: 'id',
                    render: function(data) {
                        if(window.userRole === 'pemesan'){
                            // Menggunakan fungsi pesanTiket untuk request API
                            return `<button class="btn btn-success btn-sm" onclick="pesanTiket(${data})">Pesan Tiket</button>`;
                        } else {
                            return `
                                <a href="/jadwal/edit/${data}" class="btn btn-warning btn-sm">Edit</a>
                                <button class="btn btn-danger btn-sm" onclick="deleteJadwal(${data})">Hapus</button>
                                <button class="btn btn-info btn-sm" onclick="lihatJumlahPemesan(${data})">Lihat Pemesan</button>
                            `;
                        }
                    }
                }
            ],
            language: {
                emptyTable: "<div style='text-align: center; font-weight: medium; font-size: 16px;'>Tidak ada data tersedia</div>"
            }
        });

        window.deleteJadwal = function(id) {
            let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data jadwal ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/api/jadwal/${id}`,
                        type: 'DELETE',
                        headers: { 
                            "Authorization": "Bearer " + localStorage.getItem('token'),
                            "X-CSRF-TOKEN": csrfToken,  
                            'Accept': 'application/json' 
                        },
                        success: function(response) {
                            Swal.fire('Dihapus!', 'Jadwal berhasil dihapus.', 'success');
                            $('#jadwalTable').DataTable().ajax.reload();
                        },
                        error: function(xhr) {
                            Swal.fire('Gagal!', 'Gagal menghapus data. Pastikan Anda memiliki akses.', 'error');
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
        };

        window.lihatJumlahPemesan = function(id) {
            $.ajax({
                url: `/api/jadwal/${id}`,
                type: 'GET',
                headers: { "Authorization": "Bearer " + localStorage.getItem('token') },
                success: function(response) {
                    // Bersihkan tabel pemesan
                    $('#pemesanTable tbody').empty();
                    if(response.pemesan && response.pemesan.length > 0) {
                        $.each(response.pemesan, function(index, pemesan) {
                            $('#pemesanTable tbody').append(`
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${pemesan.nama_pemesan}</td>
                                    <td>${pemesan.status_pembayaran ? pemesan.status_pembayaran : 'Belum dibayar'}</td>
                                </tr>
                            `);
                        });
                        $('#pemesanModalLabel').text(`Data Pemesan (${response.pemesan.length} pemesan)`);
                    } else {
                        $('#pemesanTable tbody').append(`
                            <tr>
                                <td colspan="3" class="text-center">Tidak ada data pemesan</td>
                            </tr>
                        `);
                        $('#pemesanModalLabel').text(`Data Pemesan (0 pemesan)`);
                    }
                    // Tampilkan modal menggunakan Bootstrap
                    var pemesanModal = new bootstrap.Modal(document.getElementById('pemesanModal'));
                    pemesanModal.show();
                },
                error: function(xhr) {
                    Swal.fire('Error', 'Gagal mengambil data pemesan.', 'error');
                }
            });
        };

        // Fungsi untuk pesan tiket melalui API
        window.pesanTiket = function(id) {
            let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            $.ajax({
                url: `/api/pesan-tiket/${id}`,
                type: 'POST',
                headers: { 
                    "Authorization": "Bearer " + localStorage.getItem('token'),
                    "X-CSRF-TOKEN": csrfToken, 
                    "Accept": "application/json"
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Tiket Dipesan!',
                        text: 'Hubungi nomor admin untuk verifikasi pembayaran'
                    });
                    // Opsional: reload tabel atau update UI jika perlu
                    $('#jadwalTable').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    Swal.fire('Error', 'Gagal memproses pemesanan tiket.', 'error');
                }
            });
        };

    });
</script>

@endsection
