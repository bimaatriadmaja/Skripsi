@extends('layouts.app')
@section('content')
    <style>
        .reset-search {
            display: inline-block;
            cursor: pointer;
            text-decoration: none;
            color: #000;
        }

        .reset-search .icon,
        .reset-search svg {
            font-size: 16px;
        }
    </style>
    <div class="main-content-inner">
        <div class="mb-10">
            <a href="{{ route('user.index') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Hasil Kerja Saya</h3>
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
                        <div class="text-tiny">Hasil Kerja</div>
                    </li>
                </ul>
            </div>
            <div class="wg-box">
                <div class="flex items-center justify-between gap-10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <form class="d-flex flex-wrap gap-2" action="{{ route('user.filter') }}" method="GET">
                            <div class="d-flex gap-3 mb-2 w-100">
                                <div class="flex-grow-1">
                                    <label for="start_date" class="form-label"
                                        style="font-size: 1.5em; font-weight: 600;">Filter Mulai:</label>
                                    <input type="date" id="start_date" class="form-control" name="start_date"
                                        value="{{ request()->input('start_date', $startDate ?? '') }}">
                                </div>
                                <div class="flex-grow-1">
                                    <label for="end_date" class="form-label"
                                        style="font-size: 1.5em; font-weight: 600;">Sampai Dengan:</label>
                                    <input type="date" id="end_date" class="form-control" name="end_date"
                                        value="{{ request()->input('end_date', $endDate ?? '') }}">
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
                            <a href="{{ route('user.filter') }}?clear_filter=true"
                                class="btn w-100 fs-5 d-flex align-items-center justify-content-center"
                                style="background-color: #6c757d; color: #ffffff; border-radius: 10px; font-size: 1.1em;">
                                <i class="bi bi-x-circle me-2"></i> Bersihkan
                            </a>
                            <a href="{{ route('export.laporan-hasil-kerja-karyawan') }}?start_date={{ request('start_date') }}&end_date={{ request('end_date') }}"
                                class="btn btn-success w-100 d-flex align-items-center justify-content-center"
                                style="font-size: 1.2em; border-radius: 10px; padding: 10px 20px;" id="export-pdf-btn-3">
                                <i class="bi bi-file-earmark-pdf me-2"></i> Export PDF
                            </a>
                            <a class="btn w-100 fs-5 d-flex align-items-center justify-content-center"
                                href="{{ route('user.hasil_kerja.add') }}"
                                style="background-color: #007bff; color: white; border-radius: 10px; font-size: 1.1em; padding: 10px 20px;">
                                <i class="icon-plus me-2"></i>Tambah Hasil
                            </a>
                        </div>
                    </div>
                </div>
                {{-- <form class="form-search" action="{{ route('user.search') }}" method="GET">
                            <fieldset class="name">
                                <input type="text" placeholder="Cari disini..." name="search" tabindex="2"
                                    aria-required="true" value="{{ request()->input('search', $search ?? '') }}">
                            </fieldset>
                            <div class="button-submit">
                                @if (request()->input('search'))
                                    <!-- Tombol reset pencarian -->
                                    <a href="{{ route('user.search') }}" class="reset-search">
                                        <i class="icon" style="font-size: 16px; color: #000;">&#x2716;</i>
                                    </a>
                                @else
                                    <!-- Tombol submit pencarian -->
                                    <button type="submit"><i class="icon-search"></i></button>
                                @endif
                            </div>
                        </form> --}}
                @if (request('start_date') && request('end_date'))
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
                                    <h5 class="card-title text-center">Laporan Keseluruhan Saya</h5>
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
                                                    <td>Rp {{ number_format($total_gaji_diambil, 0, ',', '.') }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="wg-table table-all-user mt-4">
                    <div class="table-responsive">
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
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 14rem;">Tanggal Kerja</th>
                                    <th style="width: 14rem;">Jml Genteng</th>
                                    <th style="width: 12rem;">Gaji</th>
                                    <th style="width: 16rem;">Status Hasil</th>
                                    <th style="width: 16rem;">Pembayaran</th>
                                    <th>Catatan</th>
                                    <th>Atur</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($hasilKerja as $hasil)
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
                                                Rp. {{ number_format($gaji, 0, ',', '.') }}
                                            @else
                                                <span>Belum Ditentukan</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($hasil->status == 'approved')
                                                <span class="badge bg-success fs-5 p-3">Disetujui</span>
                                            @elseif($hasil->status == 'rejected')
                                                <span class="badge bg-danger fs-5 p-3">Ditolak</span>
                                            @else
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
                                                @if ($hasil->status != 'approved' && $hasil->status != 'rejected')
                                                    <a href="{{ route('user.hasil_kerja.edit', ['id' => $hasil->id]) }}">
                                                        <div class="item edit">
                                                            <i class="icon-edit-3"></i>
                                                        </div>
                                                    </a>
                                                @endif
                                                <form action="{{ route('user.hasil_kerja.delete', $hasil->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="item text-danger delete">
                                                        <i class="icon-trash-2"></i>
                                                    </div>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">Pencarian tidak ditemukan.</td>
                                    </tr>
                                @endforelse
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
        $(function() {
            $('.delete').on('click', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');
                Swal.fire({
                    title: "Apakah kamu yakin?",
                    text: "Data akan dihapus secara permanen",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak',
                    confirmButtonColor: '#dc3545'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        @if (session('error'))
            Swal.fire({
                title: '<span style="font-size: 24px;">Gagal!</span>',
                html: '<span style="font-size: 15px;">' + @json(session('error')) + '</span>',
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545'
            });
        @endif

        @if (session('success'))
            Swal.fire({
                title: '<span style="font-size: 24px;">Sukses!</span>', 
                html: '<span style="font-size: 15px;">{{ session('success') }}</span>',
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#28a745'
            });
        @endif

        document.getElementById('export-pdf-btn-3').addEventListener('click', function(e) {
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
