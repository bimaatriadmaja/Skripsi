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
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Edit</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">
                <div class="col-lg-12">
                    <div class="page-content my-account__edit">
                        <div class="my-account__edit-form">
                            <form name="account_edit_form" action="{{ route('user.settings.update') }}" method="POST"
                                class="form-new-product form-style-1 needs-validation" novalidate="">
                                @csrf

                                @if (session('status'))
                                    <div class="alert alert-success">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <fieldset class="name">
                                    <div class="body-title">Nama Lengkap <span class="tf-color-1">*</span>
                                    </div>
                                    <input class="flex-grow" type="text" placeholder="Nama Lengkap" name="name"
                                        tabindex="0" value="{{ $user->name }}" aria-required="true" required="">
                                </fieldset>

                                <fieldset class="name">
                                    <div class="body-title">Nomor Telepon <span class="tf-color-1">*</span></div>
                                    <input class="flex-grow" type="text" placeholder="Nomor Telepon" name="mobile"
                                        tabindex="0" value="{{ $user->mobile }}" aria-required="true" required="">
                                </fieldset>

                                <fieldset class="name">
                                    <div class="body-title">Alamat Email <span class="tf-color-1">*</span></div>
                                    <input class="flex-grow" type="text" placeholder="Alamat Email" name="email"
                                        tabindex="0" value="{{ $user->email }}" aria-required="true" required="">
                                </fieldset>

                                <div class="col-md-12">
                                    <div class="my-3">
                                        <h4 class="mb-0">Isi dibawah ini jika ingin mengganti password:</h4>
                                    </div>
                                </div>
                                        <fieldset class="name">
                                            <div class="body-title pb-3">Password lama <span class="tf-color-1">*</span>
                                            </div>
                                            <input class="flex-grow" type="password" placeholder="Password lama"
                                                id="old_password" name="old_password" aria-required="true" required="">
                                        </fieldset>

                                        <fieldset class="name">
                                            <div class="body-title pb-3">Password baru <span class="tf-color-1">*</span>
                                            </div>
                                            <input class="flex-grow" type="password" placeholder="Password baru"
                                                id="new_password" name="new_password" aria-required="true" required="">
                                        </fieldset>

                                        <fieldset class="name">
                                            <div class="body-title pb-3">Konfirmasi password baru <span
                                                    class="tf-color-1">*</span></div>
                                            <input class="flex-grow" type="password" placeholder="Konfirmasi password baru"
                                                cfpwd="" data-cf-pwd="#new_password" id="new_password_confirmation"
                                                name="new_password_confirmation" aria-required="true" required="">
                                        </fieldset>
                                    <div class="col-md-12">
                                        <div class="my-3">
                                            <button type="submit" class="btn btn-primary tf-button w208">Simpan Perubahan</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
