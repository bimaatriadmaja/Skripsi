@extends('layouts.app')

@section('content')
    <div class="main-content-inner">
        <div class="mb-10">
            <a href="{{ route('user.hasil_kerja') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="main-content-wrap">
            @if (session('status'))
                <div class="alert alert-success" style="font-size: 1.5rem; padding: 20px;">
                    {{ session('status') }}
                </div>
            @endif
            <div class="flex items-center flex-wrap justify-between gap-20 mb-27">
                <h3>Tambah Hasil Kerja</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap-10">
                    <li>
                        <a href="{{ route('user.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <a href="{{ route('user.hasil_kerja') }}">
                            <div class="text-tiny">Hasil Kerja</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Tambah</div>
                    </li>
                </ul>
            </div>
            <div class="wg-box">
                <form method="POST" action="{{ route('user.hasil_kerja.store') }}">
                    @csrf
                    <fieldset class="name">
                        <div class="body-title mb-10">Tanggal Kerja <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="date" name="tanggal_kerja" tabindex="0"
                            value="{{ old('tanggal_kerja') }}" aria-required="true" required>
                        @error('tanggal_kerja')
                            <span class="alert alert-danger" style="font-size: 1.5rem; padding: 20px;">
                                {{ $message }}
                            </span>
                        @enderror
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title mb-10">Jumlah Cetak Genteng <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="number" name="jumlah_genteng" tabindex="0"
                            value="{{ old('jumlah_genteng') }}" aria-required="true" required
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')" <!-- Validasi hanya angka positif -->
                        >
                        @error('jumlah_genteng')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title mb-10">Catatan (tidak wajib diisi)</div>
                        <textarea name="catatan" rows="4" class="mb-10">{{ old('catatan') }}</textarea>
                    </fieldset>
                    <div class="text-right">
                        <button class="tf-button w-full" type="submit">Simpan Hasil Kerja</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
