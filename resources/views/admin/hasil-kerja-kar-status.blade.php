@extends('layouts.admin')

@section('content')
    <div class="main-content-inner">
        <div class="mb-10">
            <form id="backForm" action="{{ route('admin.hasil-kerja.karyawan', ['user_id' => $hasilKerja->user->id]) }}"
                method="GET">
                <button type="button" onclick="document.getElementById('backForm').submit()" class="btn btn-primary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </button>
            </form>
        </div>
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Update Status Hasil Kerja</h3>
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
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Hasil Kerja</div>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Update</div>
                    </li>
                </ul>
            </div>
            <div class="wg-box">
                <div class="wg-table">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger" style="font-size: 1.5rem; padding: 20px;">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Tanggal Kerja</th>
                                    <th>Jumlah Genteng</th>
                                    <th>Status Hasil Kerja</th>
                                    <th>Status Pembayaran</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($hasilKerja->tanggal_kerja)->translatedFormat('j F Y') }}
                                    </td>
                                    <td>{{ $hasilKerja->jumlah_genteng }}</td>
                                    <td>
                                        @if ($hasilKerja->status == 'approved')
                                            <span class="badge bg-success">Disetujui</span>
                                        @elseif($hasilKerja->status == 'rejected')
                                            <span class="badge bg-danger">Ditolak</span>
                                        @else
                                            <span class="badge bg-warning">Diproses</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($hasilKerja->payment_status == 'paid')
                                            <span class="badge bg-primary">Sudah diambil</span>
                                        @else
                                            <span class="badge bg-danger">Belum diambil</span>
                                        @endif
                                    </td>
                                    <td>{{ $hasilKerja->catatan }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <form action="{{ route('hasil-kerja.update-status', $hasilKerja->id) }}" method="POST"
                        class="p-4 border rounded shadow-sm bg-light mx-auto" style="max-width: 600px;">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $hasilKerja->user->id }}">
                        <div class="mb-5">
                            <label for="status" class="form-label fw-bold fs-5">Status Hasil Kerja:</label>
                            <select name="status" id="status" class="form-select fs-5">
                                <option value="pending" {{ $hasilKerja->status == 'pending' ? 'selected' : '' }}>Diproses
                                </option>
                                <option value="approved" {{ $hasilKerja->status == 'approved' ? 'selected' : '' }}>Setujui
                                </option>
                                <option value="rejected" {{ $hasilKerja->status == 'rejected' ? 'selected' : '' }}>Tolak
                                </option>
                            </select>
                        </div>
                        <br />
                        <div class="mb-5">
                            <label for="payment_status" class="form-label fw-bold fs-5">Status Pembayaran:</label>
                            <select name="payment_status" id="payment_status" class="form-select fs-5">
                                <option value="unpaid" {{ $hasilKerja->payment_status == 'unpaid' ? 'selected' : '' }}>
                                    Belum diambil</option>
                                <option value="paid" {{ $hasilKerja->payment_status == 'paid' ? 'selected' : '' }}>Sudah
                                    diambil</option>
                            </select>
                        </div>
                        <br />
                        <button type="submit" class="btn btn-primary w-100 fs-3">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
