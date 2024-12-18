@extends('layouts.app')

@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="#">
                <div class="row">
                    <div class="col-md-6">
                        <a href="{{ route('user.hasil_kerja') }}">
                            <div class="wg-chart-default mb-20">
                                <div class="d-flex align-items-center">
                                    <div class="image ic-bg me-3">
                                        <img src="{{ asset('assets/images/approval.png') }}" alt="Approval Icon">
                                    </div>
                                    <div>
                                        <div class="body-text mb-2">Hasil kerja saya masih menunggu Approval</div>
                                        <h4>{{ $jumlahPendingApproval }}</h4>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('user.hasil_kerja') }}">
                            <div class="wg-chart-default mb-20">
                                <div class="d-flex align-items-center">
                                    <div class="image ic-bg me-3">
                                        <img src="{{ asset('assets/images/pay.png') }}" alt="Pay Icon">
                                    </div>
                                    <div>
                                        <div class="body-text mb-2">Hasil kerja saya belum diambil gajinya</div>
                                        <h4>{{ $jumlahBelumDibayar }}</h4>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('user.hasil_kerja') }}">
                            <div class="wg-chart-default mb-20">
                                <div class="d-flex align-items-center">
                                    <div class="image ic-bg me-3">
                                        <img src="{{ asset('assets/images/rejected.png') }}" alt="Rejected Icon">
                                    </div>
                                    <div>
                                        <div class="body-text mb-2">Hasil kerja saya yang ditolak</div>
                                        <h4>{{ $jumlahHasilKerjaDitolak }}</h4>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('user.hasil_kerja') }}">
                            <div class="wg-chart-default mb-20">
                                <div class="d-flex align-items-center">
                                    <div class="image ic-bg me-3">
                                        <img src="{{ asset('assets/images/gentengimage.png') }}" alt="Genteng Icon">
                                    </div>
                                    <div>
                                        <div class="body-text mb-2">Genteng disetujui & gajinya belum diambil</div>
                                        <h4>{{ $jumlahGentengDisetujui }} biji</h4>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('user.hasil_kerja') }}">
                            <div class="wg-chart-default mb-20">
                                <div class="d-flex align-items-center">
                                    <div class="image ic-bg me-3">
                                        <img src="{{ asset('assets/images/money.png') }}" alt="Money Icon">
                                    </div>
                                    <div>
                                        <div class="body-text mb-2">Jumlah gaji yang bisa saya ambil</div>
                                        <h4>Rp {{ number_format($totalGaji, 0, ',', '.') }}</h4>
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