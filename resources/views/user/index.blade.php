@extends('layouts.app')

@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="tf-section-2 mb-30">
                <div class="flex gap20 flex-wrap-mobile">
                    <div class="w-half">
                        <a href="{{ route('user.hasil_kerja') }}" class="block">
                            <div class="wg-chart-default mb-20">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap14">
                                        <div class="image ic-bg me-3">
                                            <img src="{{ asset('assets/images/approval.png') }}" alt="Approval Icon">
                                        </div>
                                        <div>
                                            <div class="body-text mb-2">Hasil kerja saya masih menunggu Approval</div>
                                            <h4>{{ $jumlahPendingApproval }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="w-half">
                        <a href="{{ route('user.hasil_kerja') }}" class="block">
                            <div class="wg-chart-default mb-20">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap14">
                                        <div class="image ic-bg me-3">
                                            <img src="{{ asset('assets/images/pay.png') }}" alt="Pay Icon">
                                        </div>
                                        <div>
                                            <div class="body-text mb-2">Hasil kerja saya masih belum dibayar</div>
                                            <h4>{{ $jumlahBelumDibayar }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="flex gap20 flex-wrap-mobile">
                    <div class="w-half">
                        <a href="{{ route('user.hasil_kerja') }}" class="block">
                            <div class="wg-chart-default mb-20">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap14">
                                        <div class="image ic-bg me-3">
                                            <img src="{{ asset('assets/images/rejected.png') }}" alt="Rejected Icon">
                                        </div>
                                        <div>
                                            <div class="body-text mb-2">Hasil kerja saya yang ditolak</div>
                                            <h4>{{ $jumlahHasilKerjaDitolak }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="w-half">
                        <a href="{{ route('user.hasil_kerja') }}" class="block">
                            <div class="wg-chart-default mb-20">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap14">
                                        <div class="image ic-bg me-3">
                                            <img src="{{ asset('assets/images/gentengimage.png') }}" alt="Rejected Icon">
                                        </div>
                                        <div>
                                            <div class="body-text mb-2">Genteng sudah disetujui, belum dibayar</div>
                                            <h4>{{ $jumlahGentengDisetujui }} biji</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <!-- Gaji tidak link -->
                <div class="w-half">
                    <div class="wg-chart-default mb-20">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap14">
                                <div class="image ic-bg me-3">
                                    <img src="{{ asset('assets/images/money.png') }}" alt="Rejected Icon">
                                </div>
                                <div>
                                    <div class="body-text mb-2">Jumlah gaji yang bisa saya ambil</div>
                                    <h4>Rp {{ number_format($totalGaji, 0, ',', '.') }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
