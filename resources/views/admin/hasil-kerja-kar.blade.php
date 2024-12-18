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
                <div class="flex items-center justify-between gap-10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <form class="d-flex flex-wrap gap-2" action="{{ route('admin.hasil-kerja.karyawan', $karyawan->id) }}" method="GET">
                            <div class="d-flex gap-3 mb-2 w-100">
                                <!-- Filter Mulai -->
                                <div class="flex-grow-1">
                                    <label for="start_date" class="form-label" style="font-size: 1.5em; font-weight: 600;">Filter Mulai:</label>
                                    <input type="date" id="start_date" class="form-control" name="start_date" value="{{ request()->input('start_date', $startDate ?? '') }}">
                                </div>
                                <!-- Sampai Dengan -->
                                <div class="flex-grow-1">
                                    <label for="end_date" class="form-label" style="font-size: 1.5em; font-weight: 600;">Sampai Dengan:</label>
                                    <input type="date" id="end_date" class="form-control" name="end_date" value="{{ request()->input('end_date', $endDate ?? '') }}">
                                </div>
                            </div>
                        
                            <!-- Tombol Filter -->
                            <div class="d-flex gap-2 w-100">
                                <button class="btn w-100 fs-5 text-white" type="submit" style="background-color: #007bff; border-radius: 5px; font-size: 1.1em;">
                                    <i class="bi bi-filter"></i> Filter
                                </button>
                            </div>
                        </form>
                
                        <!-- Form Hasil Kerja dan Action Buttons -->
                        <div class="d-flex gap-3 mt-3 w-100">
                            <!-- Bersihkan Button -->
                            <a href="{{ route('admin.hasil-kerja.karyawan', [$karyawan->id]) }}?clear_filter=true" class="btn w-100 fs-5 d-flex align-items-center justify-content-center" style="background-color: #6c757d; color: #ffffff; border-radius: 10px; font-size: 1.1em;">
                                <i class="bi bi-x-circle me-2"></i> Bersihkan
                            </a>
                        
                            <!-- Hitung Gaji Button -->
                            <button id="hitungGajiBtn" class="btn btn-dark w-100 d-flex align-items-center justify-content-center" style="font-size: 1.2em; border-radius: 10px; padding: 10px 20px;">
                                Hitung Gaji
                            </button>
                        
                            <!-- Form Tandai Diambil -->
                            <form method="POST" action="{{ route('hasil-kerja.mark-as-paid') }}" class="d-flex align-items-center w-100">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $karyawan->id }}">
                                <button type="submit" id="markAsPaid" class="btn btn-primary w-100 d-flex align-items-center justify-content-center" style="font-size: 1.2em; border-radius: 10px; padding: 10px 20px;" @if (!session('isHitungGajiClicked')) disabled @endif>
                                    Tandai Diambil
                                </button>
                            </form>
                        
                            <!-- Export PDF Button -->
                            @if (request()->input('start_date') && request()->input('end_date'))
                                <a href="{{ route('hasil-kerja-kar.export-pdf', ['user_id' => $karyawan->id]) }}?start_date={{ request('start_date') }}&end_date={{ request('end_date') }}" class="btn btn-success w-100 d-flex align-items-center justify-content-center" style="font-size: 1.2em; border-radius: 10px; padding: 10px 20px;">
                                    <i class="bi bi-file-earmark-pdf me-2"></i> Export PDF
                                </a>
                            @else
                                <button class="btn btn-success w-100 d-flex align-items-center justify-content-center" style="font-size: 1.2em; border-radius: 10px; padding: 10px 20px;" disabled>
                                    Export PDF
                                </button>
                            @endif
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
                                            @if (isset($hasil->jumlah_genteng) && !is_null($hasil->jumlah_genteng) && isset($hasil->user->jenis_genteng))
                                                @php
                                                    $jenisGenteng = $hasil->user->jenis_genteng;
                                                    $gajiPerSeribu = $jenisGenteng ? $jenisGenteng->gaji_per_seribu : 0;
                                                    $gaji = $gajiPerSeribu * ($hasil->jumlah_genteng / 1000);
                                                @endphp
                                                <span>Rp. {{ number_format($gaji, 0, ',', '.') }}</span>
                                            @endif
                                        </td>
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
                                                <span class="badge bg-primary">Sudah diambil</span>
                                            @else
                                                <span class="badge bg-danger">Belum diambil</span>
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
    event.preventDefault(); // Mencegah reload halaman
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
                // Menampilkan error dengan SweetAlert
                Swal.fire({
                    title: 'Error!',
                    text: data.error,
                    icon: 'error',
                    confirmButtonText: 'OK',
                    focusConfirm: false,
                    position: 'center',  // Muncul di tengah
                    allowOutsideClick: false  // Tidak bisa klik di luar untuk menutup
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


        // Fungsi untuk tombol Tandai Dibayar
        // Script Anda yang sudah ada untuk proses cetak slip gaji tetap sama
document.getElementById('markAsPaid').addEventListener('click', function(event) {
    event.preventDefault(); // Mencegah pengiriman form secara langsung

    // Menampilkan SweetAlert untuk konfirmasi
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: 'Tandai hasil kerja ini sebagai "Sudah Diambil"?',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Batal',
        confirmButtonText: 'Ya, Tandai sebagai Diambil',
    }).then((result) => {
        if (result.isConfirmed) {
            // Jika pengguna menekan "Ya", submit form
            document.querySelector('form[action="{{ route('hasil-kerja.mark-as-paid') }}"]')
                .submit();
        }
    });
});


        // Mengatur SweetAlert untuk notifikasi sukses dan error
        @if (session('status'))
            Swal.fire({
                title: 'Berhasil!',
                text: '{{ session('status') }}',
                icon: 'success',
                showCancelButton: false,
                confirmButtonText: 'OK',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Menampilkan opsi untuk mencetak slip gaji
                    Swal.fire({
                        title: 'Pilih tindakan:',
                        text: 'Klik tombol untuk mencetak slip gaji',
                        icon: 'info',
                        showCancelButton: true,
                        cancelButtonText: 'Tutup',
                        confirmButtonText: 'Cetak Slip Gaji',
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
                title: 'Gagal!',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonText: 'OK',
            });
        @endif

        function validateDates() {
        var startDate = document.getElementById('start_date').value;
        var endDate = document.getElementById('end_date').value;

        if ((startDate && !endDate) || (!startDate && endDate)) {
            alert("Anda harus mengisi tanggal mulai dan tanggal akhir.");
            return false; // Prevent form submission
        }

        return true;
    }
    </script>
@endpush
