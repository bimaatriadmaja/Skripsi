@extends('layouts.admin')

@section('content')
    <div class="main-content-inner">
        <div class="mb-10">
            <a href="{{ route('admin.index') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Laporan Keseluruhan</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Laporan Keseluruhan</div>
                    </li>
                </ul>
            </div>
            <div class="wg-box">
                <div class="flex items-center justify-between gap-10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <form class="d-flex flex-wrap gap-2" action="{{ route('admin.laporan-keseluruhan.index') }}"
                            method="GET">
                            <div class="d-flex gap-3 mb-2 w-100">
                                <div class="flex-grow-1">
                                    <label for="start_date" class="form-label"
                                        style="font-size: 1.5em; font-weight: 600;">Filter Mulai:</label>
                                    <input type="date" id="start_date" class="form-control" name="start_date"
                                        value="{{ request('start_date') }}">
                                </div>
                                <div class="flex-grow-1">
                                    <label for="end_date" class="form-label"
                                        style="font-size: 1.5em; font-weight: 600;">Sampai Dengan:</label>
                                    <input type="date" id="end_date" class="form-control" name="end_date"
                                        value="{{ request('end_date') }}">
                                </div>
                            </div>
                            <div class="d-flex gap-2 w-100">
                                <button class="btn w-100 fs-5 text-white" type="submit"
                                    style="background-color: #007bff; border-radius: 10px; font-size: 1.1em; padding: 10px 20px;">
                                    <i class="bi bi-filter"></i> Filter
                                </button>
                            </div>
                        </form>
                        <div class="d-flex gap-3 mt-3 w-100">
                            <a href="{{ route('admin.laporan-keseluruhan.index') }}"
                                class="btn w-100 fs-5 d-flex align-items-center justify-content-center"
                                style="background-color: #6c757d; color: #ffffff; border-radius: 10px; font-size: 1.1em; padding: 10px 20px;">
                                <i class="bi bi-x-circle me-2"></i> Bersihkan
                            </a>
                            <a href="{{ route('admin.laporan-keseluruhan.export-pdf') }}?start_date={{ request('start_date') }}&end_date={{ request('end_date') }}"
                                class="btn btn-success w-100 d-flex align-items-center justify-content-center"
                                style="font-size: 1.2em; border-radius: 10px; padding: 10px 20px;" id="export-pdf-btn-2">
                                <i class="bi bi-file-earmark-pdf me-2"></i> Export PDF
                            </a>
                        </div>
                    </div>
                </div>
                <div class="wg-table">
                    @if (session('success'))
                        <div class="alert alert-success" style="font-size: 1.5rem; padding: 20px;">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger" style="font-size: 1.5rem; padding: 20px;">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if (request('start_date') && request('end_date'))
                        @php
                            $startDate = \Carbon\Carbon::parse(request('start_date'))
                                ->locale('id')
                                ->isoFormat('D MMMM YYYY');
                            $endDate = \Carbon\Carbon::parse(request('end_date'))
                                ->locale('id')
                                ->isoFormat('D MMMM YYYY');
                        @endphp
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <h5 class="card-title text-center">Laporan Keseluruhan Karyawan</h5>
                                        <p class="text-center"><strong>Periode: </strong>{{ $startDate }} -
                                            {{ $endDate }}</p>
                                        <div class="table-responsive" style="margin-top: 30px">
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
                                                        <td>Rp {{ number_format($total_gaji_diambil, 0, ',', '.') }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @foreach ($data as $karyawan)
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="card shadow-sm" style="margin-top: 30px">
                                        <div class="card-body">
                                            <h5 class="card-title text-center">Hasil Kerja: {{ $karyawan->karyawan_name }}
                                            </h5>
                                            <p class="text-center" style="margin-bottom: 20px"><strong>Jenis
                                                    Genteng:</strong> {{ $karyawan->nama_jenis ?? 'Tidak Ada Data' }} |
                                                <strong>Gaji per Seribu:</strong> Rp
                                                {{ number_format($karyawan->gaji_per_seribu ?? 0, 0, ',', '.') }}
                                            </p>
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
                                                            <td>{{ $karyawan->jumlah_pending_approval }}</td>
                                                            <td>{{ $karyawan->jumlah_belum_dibayar }}</td>
                                                            <td>{{ $karyawan->jumlah_sudah_dibayar }}</td>
                                                            <td>{{ $karyawan->jumlah_ditolak }}</td>
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
                                                            <td>{{ $karyawan->total_genteng_gajiblmdiambil }} Biji</td>
                                                            <td>{{ $karyawan->total_genteng_gajidiambil }} Biji</td>
                                                            <td>Rp {{ number_format($karyawan->total_gaji, 0, ',', '.') }}
                                                            </td>
                                                            <td>Rp
                                                                {{ number_format($karyawan->total_gaji_diambil, 0, ',', '.') }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p>Filter Terlebih Dahulu Untuk Mencetak Laporan.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        @if (session('error'))
            console.log('Session Error:', '{{ session('error') }}');
            Swal.fire({
                title: '<span style="font-size: 24px;">Gagal!</span>',
                html: '<span style="font-size: 15px;">' + @json(session('error')) + '</span>',
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545'
            });
        @endif

        document.getElementById('export-pdf-btn-2').addEventListener('click', function(e) {
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
