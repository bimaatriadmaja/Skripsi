@extends('layouts.app')
@section('content')
    <style>
        .reset-search {
            display: inline-block;
            cursor: pointer;
            text-decoration: none;
            color: #000;
            /* Sesuaikan warna ikon silang */
        }

        .reset-search .icon,
        .reset-search svg {
            font-size: 16px;
            /* Sesuaikan ukuran ikon silang */
        }
    </style>
    <div class="main-content-inner">
        <div class="mb-10">
            <a href="{{ route('user.index') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="main-content-wrap">
            <!-- Notifikasi pesan sukses -->
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
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
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <form class="d-flex flex-wrap gap-2" action="{{ route('user.search') }}" method="GET">
                            <!-- Kolom untuk input rentang waktu (Tanggal Mulai dan Tanggal Selesai) -->
                            <div class="d-flex gap-3 mb-2 w-100">
                                <div class="flex-grow-1">
                                    <label for="start_date" class="form-label"
                                        style="font-size: 1.5em; font-weight: 600;">Filter Mulai:</label>
                                    <!-- Tanggal Mulai -->
                                    <input type="date" id="start_date" class="form-control" name="start_date"
                                        value="{{ request()->input('start_date') }}">
                                </div>
                                <div class="flex-grow-1">
                                    <label for="end_date" class="form-label"
                                        style="font-size: 1.5em; font-weight: 600;">Sampai Dengan:</label>
                                    <!-- Tanggal Selesai -->
                                    <input type="date" id="end_date" class="form-control" name="end_date"
                                        value="{{ request()->input('end_date') }}">
                                </div>
                            </div>

                            <!-- Kolom untuk tombol Filter dan Clear Filter -->
                            <div class="d-flex gap-2 w-100">
                                <button class="btn w-100 fs-5 text-white" type="submit"
                                    style="background-color: #007bff; border-radius: 5px; font-size: 1.1em;">
                                    <i class="bi bi-filter"></i> Filter
                                </button>

                                <a href="{{ route('user.search') }}" class="btn w-100 fs-5"
                                    style="background-color: #6c757d; color: #ffffff; border-radius: 5px; font-size: 1.1em;">
                                    <i class="bi bi-x-circle"></i> Bersihkan
                                </a>
                            </div>
                        </form>


                        <form class="form-search" action="{{ route('user.search') }}" method="GET">
                            <fieldset class="name">
                                <input type="text" placeholder="Cari disini..." name="search" tabindex="2"
                                    aria-required="true" value="{{ request()->input('search') }}">
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
                        </form>
                    </div>
                    <a class="tf-button style-1 w208" href="{{ route('user.hasil_kerja.add') }}">
                        <i class="icon-plus"></i>Tambah Hasil Kerja
                    </a>
                </div>
                <div class="wg-table table-all-user mt-4">
                    <div class="table-responsive">
                        @if (Session::has('status'))
                            <p class="alert alert-success">{{ Session::get('status') }}</p>
                        @endif
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Tanggal Kerja</th>
                                    <th>Jumlah Genteng</th>
                                    <th>Status Hasil</th>
                                    <th>Pembayaran</th>
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
                                            <!-- Memberi Warna Berdasarkan Status -->
                                            @if ($hasil->status == 'approved')
                                                <span class="badge bg-success">Disetujui</span>
                                            @elseif($hasil->status == 'rejected')
                                                <span class="badge bg-danger">Ditolak</span>
                                            @else
                                                <span class="badge bg-warning">Diproses</span>
                                            @endif
                                        </td>
                                        <td>
                                            <!-- Memberi Warna Berdasarkan Status Pembayaran -->
                                            @if ($hasil->payment_status == 'paid')
                                                <span class="badge bg-primary">Sudah dibayar</span>
                                            @else
                                                <span class="badge bg-danger">Belum dibayar</span>
                                            @endif
                                        </td>
                                        <td>{{ $hasil->catatan }}</td>
                                        <td>
                                            <div class="list-icon-function">
                                                <a href="{{ route('user.hasil_kerja.edit', ['id' => $hasil->id]) }}">
                                                    <div class="item edit">
                                                        <i class="icon-edit-3"></i>
                                                    </div>
                                                </a>
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
                swal({
                    title: "Apakah kamu yakin?",
                    text: "Data akan dihapus secara permanen",
                    type: "warning",
                    buttons: ["Tidak", "Ya"],
                    confirmButtonColor: '#dc3545'
                }).then(function(result) {
                    if (result) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
