@extends('layouts.app')

@section('content')
    <div class="main-content-inner">
        <div class="mb-10">
            <a href="{{ route('user.settings.index') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Edit Profil</h3>
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
                        <a href="{{ route('user.settings.index') }}">
                            <div class="text-tiny">Profil Saya</div>
                        </a>
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
                <form action="{{ route('user.settings.update') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" id="name" class="form-control"
                            value="{{ old('name', $user->name) }}">
                        @error('name')
                            <div class="alert alert-danger" style="font-size: 1.5rem; padding: 20px;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group pt-3">
                        <label for="mobile" class="form-label">Nomor HP</label>
                        <input type="text" name="mobile" id="mobile" class="form-control"
                            value="{{ old('mobile', $user->mobile) }}" maxlength="15" pattern="\d*"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        @error('mobile')
                            <div class="alert alert-danger" style="font-size: 1.5rem; padding: 20px;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group pt-3">
                        <label for="email" class="form-label">Alamat Email</label>
                        <input type="email" name="email" id="email" class="form-control"
                            value="{{ old('email', $user->email) }}">
                        @error('email')
                            <div class="alert alert-danger" style="font-size: 1.5rem; padding: 20px;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <div class="my-3">
                            <h4 class="mb-0">Isi dibawah ini jika ingin mengganti password:</h4>
                        </div>
                    </div>
                    <div class="form-group pt-3">
                        <label for="old_password" class="form-label">Password Lama</label>
                        <input type="password" name="old_password" id="old_password" class="form-control">
                        @error('old_password')
                            <div class="alert alert-danger" style="font-size: 1.5rem; padding: 20px;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group pt-3">
                        <label for="new_password" class="form-label">Password Baru</label>
                        <input type="password" name="new_password" id="new_password" class="form-control">
                        @error('new_password')
                            <div class="alert alert-danger" style="font-size: 1.5rem; padding: 20px;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group pt-3">
                        <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                            class="form-control">
                        @error('new_password')
                            <div class="alert alert-danger" style="font-size: 1.5rem; padding: 20px;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <div class="my-3">
                            <button type="submit" class="btn btn-primary tf-button w208">Simpan Perubahan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        @if ($errors->any())
            let errorMessage = '';

            @foreach ($errors->get('name') as $error)
                errorMessage += '<strong>Nama Lengkap:</strong> {{ $error }}<br>';
            @endforeach

            @foreach ($errors->get('mobile') as $error)
                errorMessage += '<strong>Nomor HP:</strong> {{ $error }}<br>';
            @endforeach

            @foreach ($errors->get('email') as $error)
                errorMessage += '<strong>Email:</strong> {{ $error }}<br>';
            @endforeach

            @foreach ($errors->get('old_password') as $error)
                errorMessage += '<strong>Password Lama:</strong> {{ $error }}<br>';
            @endforeach

            @foreach ($errors->get('new_password') as $error)
                errorMessage += '<strong>Password Baru:</strong> {{ $error }}<br>';
            @endforeach

            @foreach ($errors->get('new_password_confirmation') as $error)
                errorMessage += '<strong>Konfirmasi Password Baru:</strong> {{ $error }}<br>';
            @endforeach

            Swal.fire({
                title: '<span style="font-size: 24px;">Gagal!</span>',
                html: '<span style="font-size: 15px;">' + errorMessage + '</span>',
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545',
            });
        @endif
    </script>
@endpush
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
