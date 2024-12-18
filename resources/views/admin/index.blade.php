@extends('layouts.admin')

@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="#">
                <div class="row">
                    <div class="col-md-6">
                        <a href="{{ route('admin.hasil-kerja.approval') }}">
                            <div class="wg-chart-default mb-20">
                                <div class="d-flex align-items-center">
                                    <div class="image ic-bg me-3">
                                        <img src="{{ asset('assets/images/approval.png') }}" alt="Approval Icon">
                                    </div>
                                    <div>
                                        <div class="body-text mb-2">Hasil kerja menunggu Approval</div>
                                        <h4>{{ $total_pending_approval }}</h4>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('admin.hasil-kerja.belum-dibayar') }}">
                            <div class="wg-chart-default mb-20">
                                <div class="d-flex align-items-center">
                                    <div class="image ic-bg me-3">
                                        <img src="{{ asset('assets/images/pay.png') }}" alt="Pay Icon">
                                    </div>
                                    <div>
                                        <div class="body-text mb-2">Hasil kerja disetujui & belum diambil gajinya</div>
                                        <h4>{{ $total_belum_dibayar }}</h4>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('admin.hasil-kerja.ditolak') }}">
                            <div class="wg-chart-default mb-20">
                                <div class="d-flex align-items-center">
                                    <div class="image ic-bg me-3">
                                        <img src="{{ asset('assets/images/rejected.png') }}" alt="Rejected Icon">
                                    </div>
                                    <div>
                                        <div class="body-text mb-2">Hasil kerja ditolak</div>
                                        <h4>{{ $total_ditolak }}</h4>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('admin.hasil-kerja.genteng') }}">
                            <div class="wg-chart-default mb-20">
                                <div class="d-flex align-items-center">
                                    <div class="image ic-bg me-3">
                                        <img src="{{ asset('assets/images/gentengimage.png') }}" alt="Genteng Icon">
                                    </div>
                                    <div>
                                        <div class="body-text mb-2">Jumlah genteng disetujui & belum diambil gajinya</div>
                                        <h4>{{ $total_genteng_disetujui }} biji</h4>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('admin.hasil-kerja.gaji') }}">
                            <div class="wg-chart-default mb-20">
                                <div class="d-flex align-items-center">
                                    <div class="image ic-bg me-3">
                                        <img src="{{ asset('assets/images/money.png') }}" alt="Money Icon">
                                    </div>
                                    <div>
                                        <div class="body-text mb-2">Gaji yang bisa diambil</div>
                                        <h4>Rp {{ number_format($total_gaji, 0, ',', '.') }}</h4>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection