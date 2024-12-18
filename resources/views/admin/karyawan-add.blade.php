@extends('layouts.admin')

@section('content')
    <div class="main-content-inner">
        <div class="mb-10">
            <a href="{{ route('admin.karyawan') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Tambah Karyawan Baru</h3>
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
                        <a href="{{ route('admin.karyawan') }}">
                            <div class="text-tiny">Karyawan</div>
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
                <form action="{{ route('admin.karyawan.store') }}" method="POST">
                    @csrf
                    @if ($errors->any())
                        <div class="alert alert-danger" style="font-size: 1.5rem; padding: 20px;">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="form-group">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="form-control form-input" required>
                    </div>
                    <div class="form-group pt-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            class="form-control form-input" required>
                    </div>
                    <div class="form-group pt-3">
                        <label for="mobile" class="form-label">Nomor HP</label>
                        <input type="text" name="mobile" id="mobile" value="{{ old('mobile') }}"
                            class="form-control form-input" required maxlength="15" pattern="\d*"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>
                    <div class="form-group pt-3">
                        <label for="jenis_genteng_id" class="form-label">Jenis Genteng</label>
                        <select name="jenis_genteng_id" id="jenis_genteng_id" class="form-select fs-5 form-input">
                            <option value="">Pilih Jenis Genteng</option>
                            @foreach ($jenisGenteng as $genteng)
                                <option value="{{ $genteng->id }}" @if (old('jenis_genteng_id') == $genteng->id) selected @endif>
                                    {{ $genteng->nama_jenis }} -
                                    Rp{{ number_format($genteng->gaji_per_seribu, 0, ',', '.') }} per 1000 biji
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group pt-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control form-input" required>
                    </div>
                    <div class="form-group pt-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="form-control form-input" required>
                    </div>
                    <button type="submit" class="btn btn-primary tf-button style-1 w208">Tambah Karyawan</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-size: 1.25rem;
            font-weight: bold;
        }

        .form-input {
            font-size: 1rem;
            padding: 0.75rem;
            border-radius: 0.375rem;
        }

        .btn-primary {
            padding: 0.75rem 1.5rem;
        }
    </style>
@endpush
