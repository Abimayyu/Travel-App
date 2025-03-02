@extends('admin.layouts.halaman')

@section('title', isset($jadwal) ? 'Edit Jadwal' : 'Tambah Jadwal')
@section('header-title', 'Kelola Jadwal')

@push('head')
<!-- SweetAlert & jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">{{ isset($jadwal) ? 'Edit Jadwal' : 'Tambah Jadwal' }}</h4>
    </div>
    <div class="card-body">
        <form id="jadwalForm">
            @csrf
            <input type="hidden" id="jadwal_id" name="jadwal_id" value="{{ $jadwal->id ?? '' }}">

            <div class="mb-3">
                <label for="tujuan" class="form-label">Tujuan</label>
                <input type="text" class="form-control" id="tujuan" name="tujuan" 
                    value="{{ old('tujuan', $jadwal->tujuan ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label for="tanggal_keberangkatan" class="form-label">Tanggal Keberangkatan</label>
                <input type="date" class="form-control" id="tanggal_keberangkatan" name="tanggal_keberangkatan" 
                    value="{{ old('tanggal_keberangkatan', $jadwal->tanggal_keberangkatan ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label for="waktu_keberangkatan" class="form-label">Waktu Keberangkatan</label>
                <input type="time" class="form-control" id="waktu_keberangkatan" name="waktu_keberangkatan" 
                    value="{{ old('waktu_keberangkatan', $jadwal->waktu_keberangkatan ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label for="kuota" class="form-label">Kuota</label>
                <input type="number" class="form-control" id="kuota" name="kuota" 
                    value="{{ old('kuota', $jadwal->kuota ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label for="harga_tiket" class="form-label">Harga Tiket</label>
                <input type="text" class="form-control" id="harga_tiket" name="harga_tiket_display"
                    value="{{ old('harga_tiket', isset($jadwal) ? 'Rp ' . number_format($jadwal->harga_tiket, 0, ',', '.') : '') }}" required>
                <input type="hidden" id="harga_tiket_hidden" name="harga_tiket" 
                    value="{{ old('harga_tiket', $jadwal->harga_tiket ?? '') }}">
            </div>

            <button type="submit" class="btn btn-primary">{{ isset($jadwal) ? 'Update Jadwal' : 'Tambah Jadwal' }}</button>
            <a href="{{ url('/jadwal') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let hargaInput = document.getElementById('harga_tiket');
        let hiddenInput = document.getElementById('harga_tiket_hidden');

        hargaInput.addEventListener('input', function(e) {
            let angka = this.value.replace(/\D/g, ''); // Hanya angka
            let formatted = new Intl.NumberFormat('id-ID').format(angka);
            this.value = 'Rp ' + formatted;
            hiddenInput.value = angka; // Simpan angka asli ke hidden input
        });

        $('#jadwalForm').on('submit', function(e) {
            e.preventDefault();

            let id = $('#jadwal_id').val();
            let url = id ? `/api/jadwal/${id}` : '/api/jadwal';
            let method = id ? 'PUT' : 'POST';
            let token = localStorage.getItem('token');
            let csrf_token = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: url,
                type: method,
                headers: { 
                    "Authorization": "Bearer " + token,
                    "X-CSRF-TOKEN": csrf_token,
                    'Accept': 'application/json'
                },
                data: $(this).serialize(),
                success: function(response) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = '/jadwal';
                    });
                },
                error: function(xhr) {
                    Swal.fire('Error!', 'Gagal menyimpan data!', 'error');
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>

@endsection
