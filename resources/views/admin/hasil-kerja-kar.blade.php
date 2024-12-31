@extends('layouts.admin')

@section('content')
    <div class="main-content-inner">
        <div class="mb-10">
            <a href="{{ route('admin.hasil-kerja-sidebar') }}" class="btn btn-primary">
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
                        <div class="text-tiny">Data Hasil Kerja</div>
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
                <div class="flex items-center justify-between gap-10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <form class="d-flex flex-wrap gap-2" action="{{ route('admin.hasil-kerja.karyawan', $karyawan->id) }}" method="GET">
                            <div class="d-flex gap-3 mb-2 w-100">
                                <div class="flex-grow-1">
                                    <label for="start_date" class="form-label" style="font-size: 1.5em; font-weight: 600;">Filter Mulai:</label>
                                    <input type="date" id="start_date" class="form-control" name="start_date" value="{{ request()->input('start_date', $startDate ?? '') }}">
                                </div>
                                <div class="flex-grow-1">
                                    <label for="end_date" class="form-label" style="font-size: 1.5em; font-weight: 600;">Sampai Dengan:</label>
                                    <input type="date" id="end_date" class="form-control" name="end_date" value="{{ request()->input('end_date', $endDate ?? '') }}">
                                </div>
                            </div>
                            <div class="d-flex gap-2 w-100">
                                <button class="btn w-100 fs-5 text-white" type="submit" style="background-color: #007bff; border-radius: 10px; font-size: 1.1em; padding: 10px 20px;">
                                    <i class="bi bi-filter"></i> Filter
                                </button>
                            </div>
                        </form>
                        <div class="d-flex gap-3 mt-3 w-100">
                            <a href="{{ route('admin.hasil-kerja.karyawan', [$karyawan->id]) }}?clear_filter=true" class="btn w-100 fs-5 d-flex align-items-center justify-content-center" style="background-color: #6c757d; color: #ffffff; border-radius: 10px; font-size: 1.1em;">
                                <i class="bi bi-x-circle me-2"></i> Bersihkan
                            </a>
                            <a href="{{ route('hasil-kerja-kar.export-pdf', ['user_id' => $karyawan->id]) }}?start_date={{ request('start_date') }}&end_date={{ request('end_date') }}" class="btn btn-success w-100 d-flex align-items-center justify-content-center" style="font-size: 1.2em; border-radius: 10px; padding: 10px 20px;" id="export-pdf-btn">
                                <i class="bi bi-file-earmark-pdf me-2"></i> Export PDF
                            </a> 
                        </div>
                        <div class="d-flex gap-3 mt-3 w-100">
                            <button id="hitungGajiBtn" class="btn btn-dark w-100 d-flex align-items-center justify-content-center" style="font-size: 1.2em; border-radius: 10px; padding: 10px 20px;">
                                Hitung Gaji
                            </button>
                            <form method="POST" action="{{ route('hasil-kerja.mark-as-paid') }}" class="d-flex align-items-center w-100">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $karyawan->id }}">
                                <button type="submit" id="markAsPaid" class="btn btn-primary w-100 d-flex align-items-center justify-content-center" style="font-size: 1.2em; border-radius: 10px; padding: 10px 20px;" @if (!session('isHitungGajiClicked')) disabled @endif>
                                    Tandai Diambil
                                </button>
                            </form>                            
                        </div>
                    </div>
                </div>
                <div id="gajiContainer" class="mt-4" style="display:none;">
                    <div class="container">
                        <div class="row justify-content-start">
                            <div class="col-md-8">
                                <div class="table-responsive">
                                    <table class="table table-borderless"
                                        style="background: transparent; border: none; width: 100%; max-width: 800px; margin: 0;">
                                        <tr>
                                            <td class="fw-bold"
                                                style="width: 20%; text-align: left; padding-right: 10px; font-size: 1.5em; border: none;">
                                                Jenis Genteng
                                            </td>
                                            <td class="text-center font-weight-bold"
                                                style="width: 10%; font-size: 1.5em; border: none;">:</td>
                                            <td class="fw-bold" style="width: 70%; font-size: 1.5em; border: none;">
                                                <span id="jenisGenteng">Kerpus</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold"
                                                style="width: 20%; text-align: left; padding-right: 10px; font-size: 1.5em; border: none;">
                                                Gaji per Seribu
                                            </td>
                                            <td class="text-center font-weight-bold"
                                                style="width: 10%; font-size: 1.5em; border: none;">:</td>
                                            <td class="fw-bold" style="width: 70%; font-size: 1.5em; border: none;">
                                                <span id="gajiPerSeribu">Rp 250.000</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold"
                                                style="width: 20%; text-align: left; padding-right: 10px; font-size: 1.5em; border: none;">
                                                Rentang Waktu
                                            </td>
                                            <td class="text-center font-weight-bold"
                                                style="width: 10%; font-size: 1.5em; border: none;">:</td>
                                            <td class="fw-bold" style="width: 70%; font-size: 1.5em; border: none;">
                                                <span id="periodeWaktu"></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold"
                                                style="width: 20%; text-align: left; padding-right: 10px; font-size: 1.5em; border: none;">
                                                Total Genteng Dihasilkan
                                            </td>
                                            <td class="text-center font-weight-bold"
                                                style="width: 10%; font-size: 1.5em; border: none;">:</td>
                                            <td class="fw-bold" style="width: 70%; font-size: 1.5em; border: none;">
                                                <span id="totalGenteng">0</span> biji
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold"
                                                style="width: 20%; text-align: left; padding-right: 10px; font-size: 1.5em; border: none;">
                                                Gaji Yang Diterima
                                            </td>
                                            <td class="text-center font-weight-bold"
                                                style="width: 10%; font-size: 1.5em; border: none;">:</td>
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
                @if (request()->input('start_date') && request()->input('end_date'))
                    @php
                        $startDate = \Carbon\Carbon::parse(request('start_date'))
                            ->locale('id')
                            ->isoFormat('D MMMM YYYY');
                        $endDate = \Carbon\Carbon::parse(request('end_date'))->locale('id')->isoFormat('D MMMM YYYY');
                    @endphp
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title text-center">Laporan {{ $karyawan->name }}</h5>
                                    <p class="text-center"><strong>Periode: </strong>{{ $startDate }} -
                                        {{ $endDate }}</p>
                                    @foreach ($laporan as $item)
                                        <p class="text-center" style="margin-bottom: 20px"><strong>Jenis
                                                Genteng:</strong> {{ $item->nama_jenis ?? 'Tidak Ada Data' }} |
                                            <strong>Gaji per Seribu:</strong> Rp
                                            {{ number_format($item->gaji_per_seribu ?? 0, 0, ',', '.') }}
                                        </p>
                                    @endforeach
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped"
                                            style="width: 100%; font-size: 1em;">
                                            <thead>
                                                <tr>
                                                    <th>Status Hasil Kerja</th>
                                                    <th>Diproses</th>
                                                    <th>Disetujui & Gaji Belum Diambil</th>
                                                    <th>Disetujui & Gaji Sudah Diambil</th>
                                                    <th>Ditolak</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Total</td>
                                                    <td>{{ $total_pending_approval }}</td>
                                                    <td>{{ $total_belum_dibayar }}</td>
                                                    <td>{{ $total_sudah_dibayar }}</td>
                                                    <td>{{ $total_ditolak }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <table class="table table-bordered table-striped"
                                            style="width: 100%; font-size: 1em;">
                                            <thead>
                                                <tr>
                                                    <th>Total Genteng (Gaji Belum Diambil)</th>
                                                    <th>Total Genteng (Gaji Sudah Diambil)</th>
                                                    <th>Gaji Belum Diambil</th>
                                                    <th>Gaji Sudah Diambil</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{ $total_genteng_gajiblmdiambil }} Biji</td>
                                                    <td>{{ $total_genteng_gajidiambil }} Biji</td>
                                                    <td>Rp {{ number_format($total_gaji, 0, ',', '.') }}</td>
                                                    <td>Rp {{ number_format($total_gaji_diambil, 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="wg-table">
                    <div class="table-responsive">
                        @if (session('success'))
                            <div class="alert alert-success" style="font-size: 1.5rem; padding: 20px;">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session('status'))
                            <div class="alert alert-success" style="font-size: 1.5rem; padding: 20px;">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger" style="font-size: 1.5rem; padding: 20px;">
                                {{ session('error') }}
                            </div>
                        @endif
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 14rem;">Tanggal Kerja</th>
                                    <th style="width: 14rem;">Jumlah Genteng</th>
                                    <th style="width: 12rem;">Gaji</th>
                                    <th style="width: 16rem;">Status</th>
                                    <th style="width: 16rem;">Pembayaran</th>
                                    <th>Catatan</th>
                                    <th>Atur</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($hasilKerja as $hasil)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($hasil->tanggal_kerja)->translatedFormat('j F Y') }}
                                        </td>
                                        <td>{{ $hasil->jumlah_genteng }} Biji</td>
                                        <td>
                                            @php
                                                $jenisGenteng = isset($hasil->user->jenis_genteng)
                                                    ? $hasil->user->jenis_genteng
                                                    : null;
                                                $gajiPerSeribu = $jenisGenteng ? $jenisGenteng->gaji_per_seribu : null;
                                            @endphp
                                            @if (isset($hasil->jumlah_genteng) && !is_null($hasil->jumlah_genteng) && $gajiPerSeribu)
                                                @php
                                                    $gaji = $gajiPerSeribu * ($hasil->jumlah_genteng / 1000);
                                                @endphp
                                                <span>Rp. {{ number_format($gaji, 0, ',', '.') }}</span>
                                            @else
                                                <span>Belum Ditentukan</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($hasil->status == 'approved')
                                                <span class="badge bg-success fs-5 p-3">Disetujui</span>
                                            @elseif($hasil->status == 'rejected')
                                                <span class="badge bg-danger fs-5 p-3">Ditolak</span>
                                            @elseif($hasil->status == 'pending')
                                                <span class="badge bg-warning fs-5 p-3">Diproses</span>
                                            @endif
                                        </td>                                        
                                        <td>
                                            @if ($hasil->payment_status == 'paid')
                                                <span class="badge bg-primary fs-5 p-3">Sudah diambil</span>
                                            @else
                                                <span class="badge bg-danger fs-5 p-3">Belum diambil</span>
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
        document.getElementById('hitungGajiBtn').addEventListener('click', function(event) {
            event.preventDefault();
            let url = `{{ route('admin.hasil-kerja.hitung-gaji', [$karyawan->id]) }}`;
            fetch(url, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        Swal.fire({
                            title: '<span style="font-size: 24px;">Gagal!</span>',
                            html: '<span style="font-size: 15px;">' + data.error + '</span>',
                            icon: 'error',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#dc3545',
                            focusConfirm: false,
                            position: 'center',
                            allowOutsideClick: false // Tidak bisa klik di luar untuk menutup
                        });
                        return;
                    }

                    // Update elemen dengan data yang diterima
                    document.getElementById('jenisGenteng').textContent = data.jenisGenteng;
                    document.getElementById('gajiPerSeribu').textContent = 'Rp ' + data.gajiPerSeribu;
                    document.getElementById('periodeWaktu').textContent = data.periode;
                    document.getElementById('totalGenteng').textContent = data.totalGenteng;
                    document.getElementById('totalGaji').textContent = data.totalGaji;

                    // Menampilkan container gaji dan mengaktifkan tombol Tandai Dibayar
                    document.getElementById('gajiContainer').style.display = 'block';
                    document.getElementById('markAsPaid').disabled = false;
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });

        document.getElementById('markAsPaid').addEventListener('click', function(event) {
            event.preventDefault();
            Swal.fire({
                title: '<span style="font-size: 24px;">Apakah Anda yakin?</span>',
                html: '<span style="font-size: 15px;">Tandai hasil kerja ini sebagai "Sudah Diambil"?</span>',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'Batal',
                confirmButtonText: 'Ya, Tandai sebagai Diambil',
                confirmButtonColor: '#28a745',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika pengguna menekan "Ya", submit form
                    document.querySelector('form[action="{{ route('hasil-kerja.mark-as-paid') }}"]')
                        .submit();
                }
            });
        });
        @if (session('status'))
            Swal.fire({
                title: '<span style="font-size: 24px;">Berhasil!</span>',
                html: '<span style="font-size: 15px;">' + @json(session('status')) + '</span>',
                icon: 'success',
                showCancelButton: false,
                confirmButtonText: 'OK',
                confirmButtonColor: '#28a745',
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: '<span style="font-size: 24px;">Pilih tindakan:</span>',
                        html: '<span style="font-size: 15px;">Klik tombol untuk mencetak slip gaji</span>',
                        icon: 'info',
                        showCancelButton: true,
                        cancelButtonText: 'Tutup',
                        confirmButtonText: 'Cetak Slip Gaji',
                        confirmButtonColor: '#28a745',
                    }).then((action) => {
                        if (action.isConfirmed) {
                            window.location.href =
                                '{{ route('admin.hasil-kerja.cetak-slip', [$karyawan->id]) }}';
                        }
                    });
                }
            });
        @elseif (session('error'))
            Swal.fire({
                title: '<span style="font-size: 24px;">Gagal!</span>',
                html: '<span style="font-size: 15px;">' + @json(session('error')) + '</span>',
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545',
            });
        @endif
    </script>
    <script>
        $(function() {
            @if (session('success'))
                Swal.fire({
                    title: '<span style="font-size: 24px;">Sukses!</span>', 
                    html: '<span style="font-size: 15px;">{{ session('success') }}</span>',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#28a745',
                });
            @endif
        });

        document.getElementById('export-pdf-btn').addEventListener('click', function(e) {
        // Cek apakah filter start_date dan end_date ada
        var startDate = new URLSearchParams(window.location.search).get('start_date');
        var endDate = new URLSearchParams(window.location.search).get('end_date');

        if (!startDate || !endDate) {
            // Jika tidak ada filter, tampilkan SweetAlert error
            e.preventDefault(); // Menghentikan pengiriman request
            Swal.fire({
                icon: 'error',
                title: '<span style="font-size: 24px;">Gagal!</span>',
                html: '<span style="font-size: 15px;">Filter terlebih dahulu untuk mencetak laporan.</span>',
                confirmButtonColor: '#dc3545',
            });
        }
    });
    </script>
@endpush