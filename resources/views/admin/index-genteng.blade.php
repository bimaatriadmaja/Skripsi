@extends('layouts.admin')

@section('content')
    <div class="main-content-inner">
        <div class="mb-4">
            <a href="{{ route('admin.index') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Genteng Disetujui & Gaji Belum Diambil</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Genteng Disetujui & Gaji Belum Diambil</div>
                    </li>
                </ul>
            </div>
            <div class="wg-box">
                @if ($karyawans->isEmpty())
                    <div class="text-center py-5">
                        <img src="{{ asset('assets/images/gentengimage.png') }}" alt="Genteng Icon">
                        <h4 style="font-size: 1.5rem; font-weight: bold; color: #6c757d;">Tidak ada genteng disetujui dan
                            belum diambil gajinya
                        </h4>
                        <p style="font-size: 1.1rem; color: #6c757d;">Semua genteng sudah disetujui dan diambil gajinya</p>
                    </div>
                @else
                    <div class="row">
                        @foreach ($karyawans as $karyawan)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card shadow border-0" style="border-radius: 12px; overflow: hidden;">
                                    <div class="card-body" style="padding: 20px;">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="image ic-bg me-3"
                                                style="width: 60px; height: 60px; border-radius: 50%; background-color: #f0f0f0; display: flex; justify-content: center; align-items: center;">
                                                <img src="{{ asset('assets/images/gentengimage.png') }}" alt="Genteng Icon">
                                            </div>
                                            <div>
                                                <h5 class="card-title mb-1" style="font-size: 1.3rem; font-weight: bold;">
                                                    {{ $karyawan->karyawan_name }}</h5>
                                                <p class="card-text text-muted mb-0" style="font-size: 1.1rem;">Total
                                                    Genteng Disetujui:</p>
                                                <p class="card-text mb-0" style="font-size: 1.2rem; font-weight: bold;">
                                                    {{ $karyawan->total_genteng_disetujui }}</p>
                                            </div>
                                        </div>
                                        <div class="text-center mt-3">
                                            <a href="{{ route('admin.hasil-kerja.karyawan', $karyawan->id) }}"
                                                class="btn btn-primary w-100"
                                                style="padding: 12px; border-radius: 15px; font-size: 1.1rem;">Lihat Detail</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
