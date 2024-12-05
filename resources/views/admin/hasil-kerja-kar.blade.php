@extends('layouts.admin')

@section('content')
    <div class="main-content-inner">
        <div class="mb-10">
            <a href="{{ route('admin.karyawan') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Hasil Kerja {{ $karyawan->name }}</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('user.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Karyawan</div>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Hasil Kerja</div>
                    </li>
                </ul>
            </div>
            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <form class="d-flex flex-wrap gap-2" action="{{ route('admin.hasil-kerja.karyawan', $karyawan->id) }}" method="GET">
                            <div class="d-flex gap-3 mb-2 w-100">
                                <div class="flex-grow-1">
                                    <label for="start_date" class="form-label" style="font-size: 1.5em; font-weight: 600;">Filter Mulai:</label>
                                    <input type="date" id="start_date" class="form-control" name="start_date" value="{{ request()->input('start_date') }}">
                                </div>
                                <div class="flex-grow-1">
                                    <label for="end_date" class="form-label" style="font-size: 1.5em; font-weight: 600;">Sampai Dengan:</label>
                                    <input type="date" id="end_date" class="form-control" name="end_date" value="{{ request()->input('end_date') }}">
                                </div>
                            </div>
                
                            <div class="d-flex gap-2 w-100">
                                <button class="btn w-100 fs-5 text-white" type="submit" style="background-color: #007bff; border-radius: 5px; font-size: 1.1em;">
                                    <i class="bi bi-filter"></i> Filter
                                </button>
                
                                <a href="{{ route('admin.hasil-kerja.karyawan', $karyawan->id) }}" class="btn w-100 fs-5" style="background-color: #6c757d; color: #ffffff; border-radius: 5px; font-size: 1.1em;">
                                    <i class="bi bi-x-circle"></i> Bersihkan
                                </a>
                            </div>
                        </form>
                    </div>
                    <div class="d-flex gap-3 mt-3">
                        <button id="hitungGajiButton" class="btn btn-dark" style="font-size: 1.2em; padding: 10px 20px;" disabled>
                            Hitung Gaji
                        </button>                        
                
                        <form method="POST" action="{{ route('hasil-kerja.mark-as-paid') }}">
                            @csrf
                            <button type="submit" id="markAsPaid" class="btn btn-primary" style="font-size: 1.2em; padding: 10px 20px; display:none;">
                                Tandai Dibayar
                            </button>
                        </form>
                    </div>
                
                    <!-- Tombol Cetak Laporan hanya muncul jika filter diterapkan -->
                    @if(request()->input('start_date') && request()->input('end_date'))
                        <a href="{{ route('hasil-kerja-kar.export-pdf', ['user_id' => $karyawan->id]) }}?start_date={{ request('start_date') }}&end_date={{ request('end_date') }}"
                            class="btn btn-success" style="font-size: 1.2em; padding: 10px 20px;">
                            Cetak Laporan
                        </a>
                    @else
                        <button class="btn btn-success" style="font-size: 1.2em; padding: 10px 20px;" disabled>
                            Cetak Laporan
                        </button>
                    @endif
                </div>
                

                <div id="gajiContainer" class="mt-4" style="display:none;">
                    <div class="container">
                        <div class="row justify-content-start"> 
                            <div class="col-md-8">
                                <div class="table-responsive">
                                    <table class="table table-borderless" style="background: transparent; border: none; width: 100%; max-width: 800px; margin: 0;">
                                        <tr>
                                            <td class="fw-bold" style="width: 20%; text-align: left; padding-right: 10px; font-size: 1.5em; border: none;">
                                                Rentang Waktu
                                            </td>
                                            <td class="text-center font-weight-bold" style="width: 10%; font-size: 1.5em; border: none;">:</td>
                                            <td class="fw-bold" style="width: 70%; font-size: 1.5em; border: none;">
                                                <span id="periodeWaktu"></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold" style="width: 20%; text-align: left; padding-right: 10px; font-size: 1.5em; border: none;">
                                                Total Genteng
                                            </td>
                                            <td class="text-center font-weight-bold" style="width: 10%; font-size: 1.5em; border: none;">:</td>
                                            <td class="fw-bold" style="width: 70%; font-size: 1.5em; border: none;">
                                                <span id="totalGenteng">0</span> biji
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold" style="width: 20%; text-align: left; padding-right: 10px; font-size: 1.5em; border: none;">
                                                Gaji
                                            </td>
                                            <td class="text-center font-weight-bold" style="width: 10%; font-size: 1.5em; border: none;">:</td>
                                            <td class="fw-bold" style="width: 70%; font-size: 1.5em; border: none;">
                                                <span id="totalGaji">Rp 0</span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                

                <div class="wg-table">
                    <div class="table-responsive">
                        @if (session('success'))
                        <div class="alert alert-success" style="font-size: 1.5rem; padding: 20px;">
                            {{ session('success') }}
                        </div>
                    @endif
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Tanggal Kerja</th>
                                    <th>Jumlah Genteng</th>
                                    <th>Status</th>
                                    <th>Pembayaran</th>
                                    <th>Catatan</th>
                                    <th>Atur</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($hasilKerja as $hasil)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($hasil->tanggal_kerja)->translatedFormat('j F Y') }}</td>
                                        <td>{{ $hasil->jumlah_genteng }}</td>
                                        <td>
                                            @if ($hasil->status == 'approved')
                                                <span class="badge bg-success">Disetujui</span>
                                            @elseif($hasil->status == 'rejected')
                                                <span class="badge bg-danger">Ditolak</span>
                                            @elseif($hasil->status == 'pending')
                                                <span class="badge bg-warning">Diproses</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($hasil->payment_status == 'paid')
                                                <span class="badge bg-primary">Sudah Dibayar</span>
                                            @else
                                                <span class="badge bg-danger">Belum Dibayar</span>
                                            @endif
                                        </td>
                                        <td>{{ $hasil->catatan }}</td>
                                        <td>
                                            <div class="list-icon-function">
                                                <a href="{{ route('hasil-kerja.edit', ['id' => $hasil->id]) }}">
                                                    <div class="item edit">
                                                        <i class="icon-edit-3"></i>
                                                    </div>
                                                    Perbarui Status
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="divider"></div>
                    <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                        {{ $hasilKerja->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
    var hitungGajiButton = document.getElementById('hitungGajiButton');

    // Periksa apakah tombol perlu dinonaktifkan saat halaman dimuat
    var karyawanId = {{ $karyawan->id }};

    fetch(`/admin/karyawan/${karyawanId}/hitung-gaji`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.totalGenteng > 0) {
                // Jika ada yang bisa dihitung, tombol diaktifkan
                hitungGajiButton.disabled = false;
            } else {
                // Jika tidak ada, tombol dinonaktifkan
                hitungGajiButton.disabled = true;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            hitungGajiButton.disabled = true; // Jika terjadi error, tetap nonaktifkan tombol
        });

    // Event listener untuk tombol ketika diklik
    hitungGajiButton.addEventListener('click', function () {
        fetch(`/admin/karyawan/${karyawanId}/hitung-gaji`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('totalGaji').innerText = 'Rp ' + data.totalGajiFormatted;
                    document.getElementById('totalGenteng').innerText = data.totalGenteng;

                    var startDate = new Date(data.startDate);
                    var endDate = new Date(data.endDate);

                    var startDateFormatted = startDate.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    });

                    var endDateFormatted = endDate.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    });

                    document.getElementById('periodeWaktu').innerText = `${startDateFormatted} - ${endDateFormatted}`;
                    document.getElementById('gajiContainer').style.display = 'block';

                    if (data.totalGaji > 0) {
                        document.getElementById('markAsPaid').style.display = 'block';
                    } else {
                        document.getElementById('markAsPaid').style.display = 'none';
                    }
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
    });
});


document.getElementById('markAsPaid').addEventListener('click', function(event) {
    event.preventDefault(); // Mencegah form submit otomatis

    var karyawanId = {{ $karyawan->id }}; 

    fetch(`/admin/hasil-kerja/mark-as-paid`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                user_id: karyawanId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.message === 'Pembayaran berhasil diperbarui.') {
                // Tidak perlu alert disini, cukup reload halaman untuk menampilkan flash message
                location.reload(); // Reload halaman untuk memperbarui tampilan dan menampilkan alert sukses
            } else {
                console.error(data.message); // Menampilkan pesan error di console
            }
        })
        .catch(error => console.error('Error:', error));
});



    </script>
@endpush