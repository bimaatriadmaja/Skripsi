@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="mb-10">
        <a href="{{ route('admin.index')}}" class="btn btn-primary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Profil Saya</h3>
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
                    <div class="text-tiny">Profil Saya</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">
            <div class="col-lg-12">
                <div class="page-content">
                    <div class="user-info">
                        <h4 class="mb-4">Informasi Admin</h4>
                        @if (session('success'))
                                <div class="alert alert-success" style="font-size: 1.5rem; padding: 20px;">
                                    {{ session('success') }}
                                </div>
                            @endif
                        <div class="user-details">
                            <p class="pt-4"><strong>Nama:</strong> {{ $admin->name }}</p>
                            <p class="pt-2"><strong>Email:</strong> {{ $admin->email }}</p>
                            <p class="pt-2"><strong>Nomor Telepon:</strong> {{ $admin->mobile }}</p>
                            <div class="pt-5">
                                <p>~ Apabila ingin mengganti password, include di dalam tombol Edit Profil ~</p>
                                <p><strong>Klik dibawah ini ya</strong></p>
                                <h4 class="py-3 px-3 mx-5">⬇️</h4>
                            </div>
                        </div>
                        <div class="my-3">
                            <a href="{{ route('admin.settings.edit') }}" class="btn btn-primary tf-button">Edit
                                Profil</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
