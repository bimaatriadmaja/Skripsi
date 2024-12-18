@extends('layouts.app')

@section('content')
    <div class="main-content-inner">
        <div class="mb-10">
            <a href="{{ route('user.index') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Profil Saya</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('user.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Profil Saya</div>
                    </li>
                </ul>
            </div>
            <div class="wg-box">
                <div class="col-lg-12">
                    <div class="page-content">
                        <div class="user-info">
                            <h4 class="mb-4">Informasi Karyawan</h4>
                            @if (session('status'))
                                <div class="alert alert-success" style="font-size: 1.5rem; padding: 20px;">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <div class="user-details">
                                <p class="pt-4"><strong>Nama:</strong> {{ $user->name }}</p>
                                <p class="pt-2"><strong>Email:</strong> {{ $user->email }}</p>
                                <p class="pt-2"><strong>Nomor Telepon:</strong> {{ $user->mobile }}</p>
                                @if ($user->jenis_genteng)
                                    <p class="pt-2"><strong>Genteng yang dicetak:</strong>
                                        {{ $user->jenis_genteng->nama_jenis }}</p>
                                    <p class="pt-2"><strong>Gaji per 1000 Genteng:</strong> Rp
                                        {{ number_format($user->jenis_genteng->gaji_per_seribu, 0, ',', '.') }}</p>
                                @else
                                    <p class="pt-2"><strong>Genteng yang dicetak:</strong> Belum diatur</p>
                                @endif
                                <div class="pt-5">
                                    <p>~ Apabila ingin mengganti password, include di dalam tombol Edit Profil ~</p>
                                    <p><strong>Klik dibawah ini</strong></p>
                                    <h4 class="py-3 px-3 mx-5">⬇️</h4>
                                </div>
                            </div>
                            <div class="my-3">
                                <a href="{{ route('user.settings.edit') }}" class="btn btn-primary tf-button">Edit
                                    Profil</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection