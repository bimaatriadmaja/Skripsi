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
                <h3>Besaran Gaji</h3>
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
                        <div class="text-tiny">Besaran Gaji</div>
                    </li>
                </ul>
            </div>
            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <a class="tf-button style-1 w208" href="{{ route('admin.jenis-genteng.add') }}">
                        <i class="icon-plus"></i>Tambah
                    </a>
                </div>
                <div class="wg-table">
                    @if (session('success'))
                        <div class="alert alert-success" style="font-size: 1.5rem; padding: 20px;">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 5rem;">No.</th>
                                    <th style="width: 18rem;">Nama Jenis</th>
                                    <th style="width: 18rem;">Gaji per Seribu</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($jenisGenteng as $index => $jenis)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $jenis->nama_jenis }}</td>
                                        <td>Rp {{ number_format($jenis->gaji_per_seribu, 0, ',', '.') }}</td>
                                        <td>
                                            <div class="list-icon-function">
                                                <a href="{{ route('admin.jenis-genteng.edit', ['id' => $jenis->id]) }}">
                                                    <div class="item edit">
                                                        <i class="icon-edit-3"></i>
                                                    </div>
                                                </a>
                                                <form
                                                    action="{{ route('admin.jenis-genteng.delete', ['id' => $jenis->id]) }}"
                                                    method="POST">
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
                                        <td colspan="4" class="text-center">Belum ada data jenis genteng.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
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
                })
            });
        });
    </script>
    @if (session('success'))
    <script>
        Swal.fire({
            title: '<span style="font-size: 24px;">Sukses</span>', 
            html: '<span style="font-size: 15px;">{{ session('success') }}</span>', 
            icon: 'success',
            confirmButtonText: 'OK',
            confirmButtonColor: '#28a745'
        });
    </script>
@endif
@endpush
