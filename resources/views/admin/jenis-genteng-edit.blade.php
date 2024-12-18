@extends('layouts.admin')

@section('content')
    <div class="main-content-inner">
        <div class="mb-10">
            <a href="{{ route('admin.jenis-genteng.index') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Edit Jenis Genteng & Gaji</h3>
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
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Edit</div>
                    </li>
                </ul>
            </div>
            <div class="wg-box">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <form action="{{ route('admin.jenis-genteng.update', $jenisGenteng->id) }}" method="POST">
                    @csrf
                    <div class="mb-5">
                        <label for="nama_jenis" class="form-label"
                            style="font-size: 1.2rem; font-weight: bold; margin-bottom: 0.7rem;">Nama Jenis Genteng</label>
                        <input type="text" name="nama_jenis" class="form-control" id="nama_jenis"
                            value="{{ old('nama_jenis', $jenisGenteng->nama_jenis) }}" required
                            style="font-size: 1.2rem; font-weight: bold; padding: 0.75rem;">
                        @error('nama_jenis')
                            <div class="text-danger" style="font-size: 1rem; font-weight: normal;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mt-5">
                        <label for="gaji_per_seribu" class="form-label"
                            style="font-size: 1.2rem; font-weight: bold; margin-bottom: 0.7rem;">Gaji per 1000 biji</label>
                        <input type="number" name="gaji_per_seribu" class="form-control" id="gaji_per_seribu"
                            value="{{ old('gaji_per_seribu', $jenisGenteng->gaji_per_seribu) }}" required
                            style="font-size: 1.2rem; font-weight: bold; padding: 0.75rem;">
                        @error('gaji_per_seribu')
                            <div class="text-danger" style="font-size: 1rem; font-weight: normal;">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary tf-button style-1 w208 mt-5"
                        style="font-size: 1.2rem; font-weight: bold;">Perbarui</button>
                </form>
            </div>
        </div>
    </div>
@endsection
