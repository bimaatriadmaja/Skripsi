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
                <h3>Data Karyawan</h3>
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
                        <div class="text-tiny">Karyawan</div>
                    </li>
                </ul>
            </div>
            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <a class="tf-button style-1 w208" href="{{ route('admin.karyawan.add') }}">
                        <i class="icon-plus"></i>Tambah
                    </a>
                </div>
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
                <div class="wg-table">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 5rem;">No.</th>
                                    <th style="width: 18rem;">Nama</th>
                                    <th style="width: 18rem;">Email</th>
                                    <th style="width: 18rem;">Nomor HP</th>
                                    <th style="width: 18rem;">Jenis Genteng</th>
                                    <th>Atur</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($karyawan as $index => $employee)
                                    <tr>
                                        <td>{{ $karyawan->firstItem() + $index }}</td>
                                        <td>{{ $employee->name }}</td>
                                        <td>{{ $employee->email }}</td>
                                        <td>{{ $employee->mobile }}</td>
                                        <td>
                                            {{ $employee->jenis_genteng ? $employee->jenis_genteng->nama_jenis : 'Belum Ditentukan' }}
                                        </td>
                                        <td>
                                            <div class="list-icon-function">
                                                {{-- <a href="{{ route('admin.karyawan.check-hasil-kerja', $employee->id) }}">
                                                    <div class="item eye">
                                                        <i class="icon-eye"></i>
                                                    </div>
                                                </a> --}}
                                                <a href="{{ route('admin.karyawan.edit', ['id' => $employee->id]) }}">
                                                    <div class="item edit">
                                                        <i class="icon-edit-3"></i>
                                                    </div>
                                                </a>
                                                <form
                                                    action="{{ route('admin.karyawan.delete', ['id' => $employee->id]) }}"
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="divider"></div>
                    <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                        {{ $karyawan->links('pagination::bootstrap-5') }}
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
