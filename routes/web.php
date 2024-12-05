<?php

use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
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

// Route::get('/home', [HomeController::class, 'index'])->name('home.index');

Route::middleware(['auth'])->group(function () {
    Route::get('/app', [UserController::class, 'index'])->name('user.index');

    // CRUD Hasil Kerja
    Route::get('/user/hasil-kerja', [UserController::class, 'hasil_kerja'])->name('user.hasil_kerja');
    Route::get('/user/hasil-kerja/add', [UserController::class, 'hasil_kerja_add'])->name('user.hasil_kerja.add');
    Route::post('/user/hasil-kerja/store', [UserController::class, 'storeHasilKerja'])->name('user.hasil_kerja.store');
    Route::get('user/hasil-kerja/{id}/edit', [UserController::class, 'hasil_kerja_edit'])->name('user.hasil_kerja.edit');
    Route::put('user/hasil-kerja/{id}', [UserController::class, 'hasil_kerja_update'])->name('user.hasil_kerja.update');
    Route::delete('user/hasil-kerja/{id}', [UserController::class, 'hasil_kerja_delete'])->name('user.hasil_kerja.delete');

    //Setting User Karyawan
    Route::get('/user/settings', [SettingsController::class, 'index'])->name('user.settings.index');
    Route::get('/user/settings/edit', [SettingsController::class, 'edit'])->name('user.settings.edit');
    Route::post('/user/settings', [SettingsController::class, 'update'])->name('user.settings.update');

    // Search Hasil Kerja
    Route::get('/user/search', [UserController::class, 'search'])->name('user.search');
});

Route::middleware(['auth', AuthAdmin::class])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

    // Lihat Karyawan
    Route::get('/admin/karyawan', [AdminController::class, 'karyawan'])->name('admin.karyawan');
    Route::get('/admin/karyawan/add', [AdminController::class, 'karyawan_add'])->name('admin.karyawan.add');
    Route::post('/admin/karyawan', [AdminController::class, 'karyawan_store'])->name('admin.karyawan.store');
    Route::get('/admin/karyawan/{id}/edit', [AdminController::class, 'karyawan_edit'])->name('admin.karyawan.edit');
    Route::put('/admin/karyawan/{id}', [AdminController::class, 'karyawan_update'])->name('admin.karyawan.update');
    Route::delete('/admin/karyawan/{id}', [AdminController::class, 'karyawan_delete'])->name('admin.karyawan.delete');

    Route::post('/admin/karyawan/{id}/set-jenis-genteng', [AdminController::class, 'setJenisGenteng'])->name('admin.karyawan.setJenisGenteng');
    Route::get('admin/karyawan/{karyawanId}/hitung-gaji', [AdminController::class, 'hitungGajiKaryawan']);
    Route::post('/admin/hasil-kerja/mark-as-paid', [AdminController::class, 'markAsPaid'])->name('hasil-kerja.mark-as-paid');


    Route::put('/admin/karyawan/{id}/update-jenis-genteng', [AdminController::class, 'updateJenisGenteng'])->name('admin.updateJenisGenteng');
    Route::get('/admin/karyawan/create', [AdminController::class, 'create'])->name('admin.karyawan.create');


    // lihat hasil kerja
    // Rute untuk menuju halaman hasil kerja masing-masing karyawan
    Route::get('/admin/hasil-kerja/{user_id}', [AdminController::class, 'lihatHasilfromIndex'])->name('admin.index-hasilkerja');
    Route::get('/admin/hasil-kerja/{user_id}', [AdminController::class, 'hasilKerjaByKaryawan'])->name('admin.hasil-kerja.karyawan');
    Route::get('/hasil-kerja/{id}/edit', [HasilKerjaController::class, 'edit'])->name('hasil-kerja.edit');
    Route::post('/hasil-kerja/{id}/update-status', [HasilKerjaController::class, 'updateStatus'])->name('hasil-kerja.update-status');
    Route::post('/admin/hasil-kerja/update-payment-status', [AdminController::class, 'updatePaymentStatus'])->name('hasil-kerja.update-payment-status');
    Route::get('/admin/hasil-kerja/{user_id}/export-pdf', [PdfExportController::class, 'exportHasilKerja'])
    ->name('hasil-kerja-kar.export-pdf');

    //Setting Admin
    Route::get('/admin/settings', [SettingsController::class, 'admin_index'])->name('admin.settings.index');
    Route::get('/admin/settings/edit', [SettingsController::class, 'admin_edit'])->name('admin.settings.edit');
    Route::post('/admin/settings', [SettingsController::class, 'admin_update'])->name('admin.settings.update');

    Route::get('/admin/coba', [SettingsController::class, 'coba_index'])->name('admin.coba');

    // jenis genteng
    Route::get('/admin/jenis-genteng', [JenisGentengController::class, 'index'])->name('admin.jenis-genteng.index');
    Route::get('/admin/jenis-genteng/create', [JenisGentengController::class, 'jenis_genteng_add'])->name('admin.jenis-genteng.add'); // Menampilkan form tambah jenis genteng
    Route::post('/admin/jenis-genteng/store', [JenisGentengController::class, 'jenis_genteng_store'])->name('admin.jenis-genteng.store'); // Menyimpan jenis genteng baru
    Route::get('/admin/jenis-genteng/edit/{id}', [JenisGentengController::class, 'jenis_genteng_edit'])->name('admin.jenis-genteng.edit'); // Menampilkan form edit jenis genteng
    Route::post('/admin/jenis-genteng/update/{id}', [JenisGentengController::class, 'jenis_genteng_update'])->name('admin.jenis-genteng.update'); // Memperbarui jenis genteng
    Route::delete('/admin/jenis-genteng/delete/{id}', [JenisGentengController::class, 'jenis_genteng_delete'])->name('admin.jenis-genteng.delete'); // Menghapus jenis genteng
});