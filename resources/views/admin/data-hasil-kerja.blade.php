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
                <h3>Data Hasil Kerja</h3>
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
                        <div class="text-tiny">Data Hasil Kerja</div>
                    </li>
                </ul>
            </div>
            <div class="wg-box">
                @if ($karyawan->isEmpty())
                    <div class="text-center py-5">
                        <h4 style="font-size: 1.5rem; font-weight: bold; color: #6c757d;">Tidak ada karyawan yang terdaftar.
                        </h4>
                    </div>
                @else
                    @if (session('error'))
                        <div class="alert alert-danger" style="font-size: 1.5rem; padding: 20px;">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="row">
                        @foreach ($karyawan as $employee)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card shadow border-0"
                                    style="border-radius: 12px; overflow: hidden; margin-top:10px">
                                    <div class="card-body" style="padding: 20px;">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="image ic-bg me-3"
                                                style="width: 60px; height: 60px; border-radius: 50%; background-color: #f0f0f0; display: flex; justify-content: center; align-items: center;">
                                                <img src="{{ asset('assets/images/result.png') }}" alt="Approval Icon">
                                            </div>
                                            <div>
                                                <h5 class="card-title mb-1" style="font-size: 1.5rem; font-weight: bold;">
                                                    {{ $employee->name }}
                                                </h5>
                                                <p class="card-text text-muted mb-0" style="font-size: 1.2rem;">
                                                    Jenis Genteng:
                                                </p>
                                                <p class="card-text mb-0" style="font-size: 1.4rem; font-weight: bold;">
                                                    {{ $employee->jenis_genteng ? $employee->jenis_genteng->nama_jenis : 'Belum Ditentukan' }}
                                                </p>
                                                <p class="card-text text-muted mb-0" style="font-size: 1.2rem;">
                                                    Gaji Per Seribu:
                                                </p>
                                                <p class="card-text mb-0" style="font-size: 1.4rem; font-weight: bold;">
                                                    Rp.
                                                    {{ $employee->jenis_genteng ? number_format($employee->jenis_genteng->gaji_per_seribu, 0, ',', '.') : '0' }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="text-center mt-3">
                                            <a href="{{ route('admin.karyawan.check-hasil-kerja', $employee->id) }}"
                                                class="btn btn-primary w-100"
                                                style="padding: 12px; border-radius: 15px; font-size: 1.5rem;">
                                                Lihat Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                    {{ $karyawan->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        @if (session('error'))
            console.log('Session Error:', '{{ session('error') }}');
            Swal.fire({
                title: '<span style="font-size: 24px;">Gagal!</span>',
                html: '<span style="font-size: 15px;">' + @json(session('error')) + '</span>',
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545',
            });
        @endif
    </script>
@endpush
