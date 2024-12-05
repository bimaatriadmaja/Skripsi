@extends('layouts.app')

@section('content')
    <div class="main-content-inner">
        <div class="mb-10">
            <a href="{{ route('user.hasil_kerja')}}" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
        <!-- main-content-wrap -->
        <div class="main-content-wrap">
            <!-- Notifikasi pesan sukses -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
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
            <!-- form-add-hasil-kerja -->
            <div class="wg-box">
                <form method="POST" action="{{ route('user.hasil_kerja.store') }}">
                    @csrf

                    <fieldset class="name">
                        <div class="body-title mb-10">Tanggal Kerja <span class="tf-color-1">*</span></div>
                        <input 
                            class="mb-10" 
                            type="date" 
                            name="tanggal_kerja" 
                            tabindex="0" 
                            value="{{ old('tanggal_kerja') }}" 
                            aria-required="true" 
                            required
                        >
                        @error('tanggal_kerja')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title mb-10">Jumlah Cetak Genteng <span class="tf-color-1">*</span></div>
                        <input 
                            class="mb-10" 
                            type="number" 
                            name="jumlah_genteng" 
                            tabindex="0" 
                            value="{{ old('jumlah_genteng') }}" 
                            aria-required="true" 
                            required
                        >
                        @error('jumlah_genteng')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title mb-10">Catatan (tidak wajib diisi)</div>
                        <textarea 
                            name="catatan" 
                            rows="4" 
                            class="mb-10"
                        >{{ old('catatan') }}</textarea>
                    </fieldset>

                    <div class="text-right">
                        <button class="tf-button w-full" type="submit">Simpan Hasil Kerja</button>
                    </div>
                </form>
            </div>
            <!-- /form-add-hasil-kerja -->
        </div>
        <!-- /main-content-wrap -->
    </div>
@endsection
