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
                <h3>Pengaturan Gaji</h3>
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
                        <div class="text-tiny">Pengaturan Gaji</div>
                    </li>
                </ul>
            </div>
            <div class="wg-box">
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
                                    <th>Nama Jenis</th>
                                    <th>Gaji per Seribu</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($jenisGenteng as $index => $jenis)
                                    <tr>
                                        <td>{{ $jenis->nama_jenis }}</td>
                                        <td>Rp {{ number_format($jenis->gaji_per_seribu, 0, ',', '.') }}</td>
                                        <td>
                                            <div class="list-icon-function">
                                                {{-- {{ route('admin.jenis-genteng.edit', ['id' => $jenis->id]) }} --}}
                                                <a href="{{ route('admin.jenis-genteng.edit', ['id' => $jenis->id]) }}">
                                                    <div class="item edit">
                                                        <i class="icon-edit-3"></i>
                                                    </div>
                                                </a>
                                                <form action="{{ route('admin.jenis-genteng.delete', ['id' => $jenis->id]) }}" method="POST">
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
            <div class="wg-box mt-5">
                <div class="flex items-center justify-between gap10 flex-wrap mb-4">
                    <h4>Tambah Jenis Genteng & Gaji</h4>
                </div>
                <form action="{{ route('admin.jenis-genteng.store') }}" method="POST">
                    @csrf
                    <div class="mb-5">
                        <label for="nama_jenis" class="form-label" style="font-size: 1.2rem; font-weight: bold; margin-bottom: 0.7rem;">Nama Jenis</label>
                        <input type="text" class="form-control" id="nama_jenis" name="nama_jenis" required style="font-size: 1.2rem; font-weight: bold; padding: 0.75rem;">
                    </div>
                    <div class="mt-5">
                        <label for="gaji_per_seribu" class="form-label" style="font-size: 1.2rem; font-weight: bold; margin-bottom: 0.7rem;">Gaji per Seribu</label>
                        <input type="number" class="form-control" id="gaji_per_seribu" name="gaji_per_seribu" required style="font-size: 1.2rem; font-weight: bold; padding: 0.75rem;">
                    </div>
                    <button type="submit" class="btn btn-primary tf-button style-1 w208 mt-5">Tambah</button>
                </form>
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
@endpush