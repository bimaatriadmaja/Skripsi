<?php

use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\PdfExportController;
use App\Http\Controllers\HasilKerjaController;
use App\Http\Controllers\JenisGentengController;

Auth::routes();

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/app', [UserController::class, 'index'])->name('user.index');

    // Kelola Hasil Kerja
    Route::get('/user/hasil-kerja', [UserController::class, 'hasil_kerja'])->name('user.hasil_kerja');
    Route::get('/user/hasil-kerja/add', [UserController::class, 'hasil_kerja_add'])->name('user.hasil_kerja.add');
    Route::post('/user/hasil-kerja/store', [UserController::class, 'storeHasilKerja'])->name('user.hasil_kerja.store');
    Route::get('user/hasil-kerja/{id}/edit', [UserController::class, 'hasil_kerja_edit'])->name('user.hasil_kerja.edit');
    Route::put('user/hasil-kerja/{id}', [UserController::class, 'hasil_kerja_update'])->name('user.hasil_kerja.update');
    Route::delete('user/hasil-kerja/{id}', [UserController::class, 'hasil_kerja_delete'])->name('user.hasil_kerja.delete');
    Route::get('/user/filter', [UserController::class, 'filter'])->name('user.filter');
    Route::get('user/laporan-karyawan/export-pdf', [PdfExportController::class, 'exportLaporanHasilKerjaKaryawan'])->name('export.laporan-hasil-kerja-karyawan');

    // Setting Karyawan
    Route::get('/user/settings', [SettingsController::class, 'index'])->name('user.settings.index');
    Route::get('/user/settings/edit', [SettingsController::class, 'edit'])->name('user.settings.edit');
    Route::post('/user/settings', [SettingsController::class, 'update'])->name('user.settings.update');

    // Cetak laporan 

});

Route::middleware(['auth', AuthAdmin::class])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

    // view karyawan
    Route::get('/admin/karyawan', [AdminController::class, 'karyawan'])->name('admin.karyawan');
    Route::get('/admin/karyawan/add', [AdminController::class, 'karyawan_add'])->name('admin.karyawan.add');
    Route::post('/admin/karyawan', [AdminController::class, 'karyawan_store'])->name('admin.karyawan.store');
    Route::get('/admin/karyawan/{id}/edit', [AdminController::class, 'karyawan_edit'])->name('admin.karyawan.edit');
    Route::put('/admin/karyawan/{id}', [AdminController::class, 'karyawan_update'])->name('admin.karyawan.update');
    Route::delete('/admin/karyawan/{id}', [AdminController::class, 'karyawan_delete'])->name('admin.karyawan.delete');
    Route::get('/admin/karyawan/{id}/check-hasil-kerja', [HasilKerjaController::class, 'checkHasilKerja'])->name('admin.karyawan.check-hasil-kerja');

    // view hasil kerja
    Route::get('/admin/hasil-kerja/{user_id}', [AdminController::class, 'hasilKerjaByKaryawan'])->name('admin.hasil-kerja.karyawan');
// Rute untuk halaman hitung gaji
    Route::get('/admin/hasil-kerja/hitung-gaji/{user_id}', [AdminController::class, 'hitungGaji'])->name('admin.hasil-kerja.hitung-gaji');
    Route::post('/admin/hasil-kerja/mark-as-paid', [AdminController::class, 'markAsPaid'])->name('hasil-kerja.mark-as-paid');
    Route::get('admin/hasil-kerja/cetak-slip/{karyawanId}', [PdfExportController::class, 'cetakSlipGaji'])->name('admin.hasil-kerja.cetak-slip');

    // Update Status
    Route::get('/hasil-kerja/{id}/edit', [HasilKerjaController::class, 'edit'])->name('hasil-kerja.edit');
    Route::post('/hasil-kerja/{id}/update-status', [HasilKerjaController::class, 'updateStatus'])->name('hasil-kerja.update-status');
    Route::get('/admin/hasil-kerja/{user_id}/export-pdf', [PdfExportController::class, 'exportHasilKerja'])->name('hasil-kerja-kar.export-pdf');
    Route::get('admin/laporan-keseluruhan/export-pdf', [PdfExportController::class, 'exportLaporanKeseluruhan'])->name('admin.laporan-keseluruhan.export-pdf');

    //Setting Admin
    Route::get('/admin/settings', [SettingsController::class, 'admin_index'])->name('admin.settings.index');
    Route::get('/admin/settings/edit', [SettingsController::class, 'admin_edit'])->name('admin.settings.edit');
    Route::post('/admin/settings', [SettingsController::class, 'admin_update'])->name('admin.settings.update');

    // Jenis genteng
    Route::get('/admin/jenis-genteng', [JenisGentengController::class, 'index'])->name('admin.jenis-genteng.index');
    Route::post('/admin/jenis-genteng/store', [JenisGentengController::class, 'jenis_genteng_store'])->name('admin.jenis-genteng.store');
    Route::get('/admin/jenis-genteng/edit/{id}', [JenisGentengController::class, 'jenis_genteng_edit'])->name('admin.jenis-genteng.edit');
    Route::post('/admin/jenis-genteng/update/{id}', [JenisGentengController::class, 'jenis_genteng_update'])->name('admin.jenis-genteng.update');
    Route::delete('/admin/jenis-genteng/delete/{id}', [JenisGentengController::class, 'jenis_genteng_delete'])->name('admin.jenis-genteng.delete');

    // View Untuk Laporan Keseluruhan
    Route::get('admin/approval', [AdminController::class, 'showApproval'])->name('admin.hasil-kerja.approval');
    Route::get('admin/belum-dibayar', [AdminController::class, 'showBelumDibayar'])->name('admin.hasil-kerja.belum-dibayar');
    Route::get('admin/ditolak', [AdminController::class, 'showDitolak'])->name('admin.hasil-kerja.ditolak');
    Route::get('admin/genteng', [AdminController::class, 'showGenteng'])->name('admin.hasil-kerja.genteng');
    Route::get('admin/gaji', [AdminController::class, 'showGaji'])->name('admin.hasil-kerja.gaji');

    // cetak laporan keseluruhan
    Route::get('/admin/laporan/keseluruhan', [AdminController::class, 'laporan_index'])->name('admin.laporan-keseluruhan.index');
});