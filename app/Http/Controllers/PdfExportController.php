<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\HasilKerja;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;

class PdfExportController extends Controller
{
    public function exportHasilKerja($user_id, Request $request)
    {
        // Cek hak akses pengguna
        if (Auth::user()->utype !== 'ADM') {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses');
        }
    
        // Ambil filter tanggal dari request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
    
        // Ambil data karyawan berdasarkan ID
        $karyawan = User::findOrFail($user_id);
        $jenisGenteng = $karyawan->jenis_genteng;
    
        // Query untuk mendapatkan data hasil kerja
        $query = HasilKerja::where('user_id', $user_id)
            ->leftJoin('users as u', 'hasil_kerja.user_id', '=', 'u.id')
            ->leftJoin('jenis_genteng as jg', 'u.jenis_genteng_id', '=', 'jg.id')
            ->select(
                'hasil_kerja.user_id',
                'u.name',
                'hasil_kerja.status',
                'hasil_kerja.payment_status',
                'hasil_kerja.jumlah_genteng',
                'jg.nama_jenis',
                'jg.gaji_per_seribu',
                'hasil_kerja.tanggal_kerja',
                'hasil_kerja.catatan',
                DB::raw('hasil_kerja.jumlah_genteng * jg.gaji_per_seribu / 1000 as gaji'),
                DB::raw('COUNT(CASE WHEN hasil_kerja.status = "pending" THEN 1 END) as jumlah_pending_approval'),
                DB::raw('COUNT(CASE WHEN hasil_kerja.status = "approved" AND hasil_kerja.payment_status = "unpaid" THEN 1 END) as jumlah_belum_dibayar'),
                DB::raw('COUNT(CASE WHEN hasil_kerja.status = "approved" AND hasil_kerja.payment_status = "paid" THEN 1 END) as jumlah_sudah_dibayar'),
                DB::raw('COUNT(CASE WHEN hasil_kerja.status = "rejected" THEN 1 END) as jumlah_ditolak'),
                DB::raw('SUM(CASE WHEN hasil_kerja.status = "approved" AND hasil_kerja.payment_status = "unpaid" THEN hasil_kerja.jumlah_genteng ELSE 0 END) as total_genteng_gajiblmdiambil'),
                DB::raw('SUM(CASE WHEN hasil_kerja.status = "approved" AND hasil_kerja.payment_status = "paid" THEN hasil_kerja.jumlah_genteng ELSE 0 END) as total_genteng_gajidiambil'),
                DB::raw('SUM(CASE WHEN hasil_kerja.status = "approved" AND hasil_kerja.payment_status = "unpaid" THEN hasil_kerja.jumlah_genteng * jg.gaji_per_seribu / 1000 ELSE 0 END) as total_gaji'),
                DB::raw('SUM(CASE WHEN hasil_kerja.status = "approved" AND hasil_kerja.payment_status = "paid" THEN hasil_kerja.jumlah_genteng * jg.gaji_per_seribu / 1000 ELSE 0 END) as total_gaji_diambil')
            )
            ->groupBy('hasil_kerja.user_id', 'u.name', 'hasil_kerja.status', 'hasil_kerja.payment_status', 'hasil_kerja.jumlah_genteng', 'jg.nama_jenis', 'jg.gaji_per_seribu', 'hasil_kerja.tanggal_kerja', 'hasil_kerja.catatan');
    
        // Filter berdasarkan tanggal
        if ($startDate) {
            $query->whereDate('hasil_kerja.tanggal_kerja', '>=', $startDate);
        }
    
        if ($endDate) {
            $query->whereDate('hasil_kerja.tanggal_kerja', '<=', $endDate);
        }
    
        $query->orderBy('hasil_kerja.tanggal_kerja', 'asc');
    
        // Ambil data hasil kerja yang sudah difilter
        $hasilKerja = $query->get();
    
        // Pastikan data hasil kerja ada
        if ($hasilKerja->isEmpty()) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }
    
        // Perhitungan total
        $totalPendingApproval = $hasilKerja->sum('jumlah_pending_approval');
        $totalBelumDibayar = $hasilKerja->sum('jumlah_belum_dibayar');
        $totalSudahDibayar = $hasilKerja->sum('jumlah_sudah_dibayar');
        $totalDitolak = $hasilKerja->sum('jumlah_ditolak');
        $totalGentengGajiBelumDiambil = $hasilKerja->sum('total_genteng_gajiblmdiambil');
        $totalGentengGajiSudahDiambil = $hasilKerja->sum('total_genteng_gajidiambil');
        $totalGaji = $hasilKerja->sum('total_gaji');
        $totalGajiDiambil = $hasilKerja->sum('total_gaji_diambil');
    
        // Format tanggal untuk digunakan di view PDF
        $startDateFormatted = $startDate ? Carbon::parse($startDate)->locale('id')->translatedFormat('j F Y') : 'Tidak ditentukan';
        $endDateFormatted = $endDate ? Carbon::parse($endDate)->locale('id')->translatedFormat('j F Y') : 'Tidak ditentukan';
    
        // Ambil data pertama untuk pertama kali sebagai contoh
        $firstData = $hasilKerja->first();
    
        // Membuat PDF dari view
        $pdf = Pdf::loadView('pdf.hasil-kerja-kar', compact(
            'karyawan',
            'hasilKerja',
            'totalPendingApproval',
            'totalBelumDibayar',
            'totalSudahDibayar',
            'totalDitolak',
            'totalGentengGajiBelumDiambil',
            'totalGentengGajiSudahDiambil',
            'totalGaji',
            'totalGajiDiambil',
            'firstData',  // Pastikan ini ada
            'startDateFormatted',
            'endDateFormatted',
        ))
            ->setPaper('a4', 'portrait'); // Mengatur ukuran kertas A4 dan orientasi portrait
    
        // Mengunduh file PDF
        return $pdf->download('hasil_kerja-' . $karyawan->name . '.pdf');
    }
    
    // aman
    public function exportLaporanKeseluruhan()
    {
        $query = User::where('utype', 'USR')
            ->leftJoin('hasil_kerja as hk', 'users.id', '=', 'hk.user_id')
            ->leftJoin('jenis_genteng as jg', 'users.jenis_genteng_id', '=', 'jg.id')
            ->select(
                'users.id',
                'users.name as karyawan_name',
                'jg.nama_jenis', // Kolom tambahan
                'jg.gaji_per_seribu',
                DB::raw('COUNT(CASE WHEN hk.status = "pending" THEN 1 END) as jumlah_pending_approval'),
                DB::raw('COUNT(CASE WHEN hk.status = "approved" AND hk.payment_status = "unpaid" THEN 1 END) as jumlah_belum_dibayar'),
                DB::raw('COUNT(CASE WHEN hk.status = "approved" AND hk.payment_status = "paid" THEN 1 END) as jumlah_sudah_dibayar'),
                DB::raw('COUNT(CASE WHEN hk.status = "rejected" THEN 1 END) as jumlah_ditolak'),
                DB::raw('SUM(CASE WHEN hk.status = "approved" AND hk.payment_status = "unpaid" THEN hk.jumlah_genteng ELSE 0 END) as total_genteng_gajiblmdiambil'),
                DB::raw('SUM(CASE WHEN hk.status = "approved" AND hk.payment_status = "paid" THEN hk.jumlah_genteng ELSE 0 END) as total_genteng_gajidiambil'),
                DB::raw('SUM(CASE WHEN hk.status = "approved" AND hk.payment_status = "unpaid" THEN hk.jumlah_genteng * jg.gaji_per_seribu / 1000 ELSE 0 END) as total_gaji'),
                DB::raw('SUM(CASE WHEN hk.status = "approved" AND hk.payment_status = "paid" THEN hk.jumlah_genteng * jg.gaji_per_seribu / 1000 ELSE 0 END) as total_gaji_diambil')
            )
            ->groupBy('users.id', 'jg.nama_jenis', 'jg.gaji_per_seribu');

        if ($start_date = request('start_date')) {
            $query->whereDate('hk.tanggal_kerja', '>=', $start_date);
        }
        if ($end_date = request('end_date')) {
            $query->whereDate('hk.tanggal_kerja', '<=', $end_date);
        }

        $data = $query->get();

        // Menghitung total keseluruhan untuk bagian laporan keseluruhan
        $total_pending_approval = $data->sum('jumlah_pending_approval');
        $total_belum_dibayar = $data->sum('jumlah_belum_dibayar');
        $total_sudah_dibayar = $data->sum('jumlah_sudah_dibayar');
        $total_ditolak = $data->sum('jumlah_ditolak');
        $total_genteng_gajiblmdiambil = $data->sum('total_genteng_gajiblmdiambil');
        $total_genteng_gajidiambil = $data->sum('total_genteng_gajidiambil');
        $total_gaji = $data->sum('total_gaji');
        $total_gaji_diambil = $data->sum('total_gaji_diambil');

        // Generate PDF
        $pdf = PDF::loadView('pdf.laporan-keseluruhan', compact(
            'data',
            'total_pending_approval',
            'total_belum_dibayar',
            'total_sudah_dibayar',
            'total_ditolak',
            'total_genteng_gajiblmdiambil',
            'total_genteng_gajidiambil',
            'total_gaji',
            'total_gaji_diambil'
        ))
            ->setPaper('a4', 'potrait');

        return $pdf->download('laporan-keseluruhan.pdf');
    }

    // aman
    public function exportLaporanHasilKerjaKaryawan(Request $request)
    {
        $user = Auth::user();

        // Ambil input dari form filter
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        // Validasi tanggal (tanggal mulai dan tanggal akhir tidak boleh lebih dari hari ini)
        $today = date('Y-m-d');

        if ($start_date && strtotime($start_date) > strtotime($today)) {
            return redirect()->back()->with('error', 'Tanggal mulai tidak boleh lebih dari hari ini');
        }

        if ($end_date && strtotime($end_date) > strtotime($today)) {
            return redirect()->back()->with('error', 'Tanggal akhir tidak boleh lebih dari hari ini');
        }

        if ($start_date && $end_date && strtotime($start_date) > strtotime($end_date)) {
            return redirect()->back()->with('error', 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
        }

        // Format tanggal untuk digunakan di view PDF
        $startDateFormatted = $start_date ? \Carbon\Carbon::parse($start_date)->locale('id')->translatedFormat('j F Y') : null;
        $endDateFormatted = $end_date ? \Carbon\Carbon::parse($end_date)->locale('id')->translatedFormat('j F Y') : null;

        // Query untuk filter berdasarkan hasil kerja
        $query = HasilKerja::where('user_id', $user->id)
            ->leftJoin('users as u', 'hasil_kerja.user_id', '=', 'u.id')
            ->leftJoin('jenis_genteng as jg', 'u.jenis_genteng_id', '=', 'jg.id')
            ->select(
                'hasil_kerja.user_id',
                'u.name',
                'hasil_kerja.status',
                'hasil_kerja.payment_status',
                'hasil_kerja.jumlah_genteng',
                'jg.nama_jenis',
                'jg.gaji_per_seribu',
                'hasil_kerja.tanggal_kerja',
                'hasil_kerja.catatan',
                DB::raw('hasil_kerja.jumlah_genteng * jg.gaji_per_seribu / 1000 as gaji'),
                DB::raw('COUNT(CASE WHEN hasil_kerja.status = "pending" THEN 1 END) as jumlah_pending_approval'),
                DB::raw('COUNT(CASE WHEN hasil_kerja.status = "approved" AND hasil_kerja.payment_status = "unpaid" THEN 1 END) as jumlah_belum_dibayar'),
                DB::raw('COUNT(CASE WHEN hasil_kerja.status = "approved" AND hasil_kerja.payment_status = "paid" THEN 1 END) as jumlah_sudah_dibayar'),
                DB::raw('COUNT(CASE WHEN hasil_kerja.status = "rejected" THEN 1 END) as jumlah_ditolak'),
                DB::raw('SUM(CASE WHEN hasil_kerja.status = "approved" AND hasil_kerja.payment_status = "unpaid" THEN hasil_kerja.jumlah_genteng ELSE 0 END) as total_genteng_gajiblmdiambil'),
                DB::raw('SUM(CASE WHEN hasil_kerja.status = "approved" AND hasil_kerja.payment_status = "paid" THEN hasil_kerja.jumlah_genteng ELSE 0 END) as total_genteng_gajidiambil'),
                DB::raw('SUM(CASE WHEN hasil_kerja.status = "approved" AND hasil_kerja.payment_status = "unpaid" THEN hasil_kerja.jumlah_genteng * jg.gaji_per_seribu / 1000 ELSE 0 END) as total_gaji'),
                DB::raw('SUM(CASE WHEN hasil_kerja.status = "approved" AND hasil_kerja.payment_status = "paid" THEN hasil_kerja.jumlah_genteng * jg.gaji_per_seribu / 1000 ELSE 0 END) as total_gaji_diambil')
            )
            ->groupBy('hasil_kerja.user_id', 'u.name', 'hasil_kerja.status', 'hasil_kerja.payment_status', 'hasil_kerja.jumlah_genteng', 'jg.nama_jenis', 'jg.gaji_per_seribu', 'hasil_kerja.tanggal_kerja', 'hasil_kerja.catatan',);

        // Filter berdasarkan tanggal
        if ($start_date) {
            $query->whereDate('hasil_kerja.tanggal_kerja', '>=', $start_date);
        }

        if ($end_date) {
            $query->whereDate('hasil_kerja.tanggal_kerja', '<=', $end_date);
        }
        $query->orderBy('hasil_kerja.tanggal_kerja', 'asc');

        // Ambil data yang sudah difilter
        $data = $query->get();

        // Jika data kosong, kembalikan error
        if ($data->isEmpty()) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        // Total untuk seluruh data
        $total_pending_approval = $data->sum('jumlah_pending_approval');
        $total_belum_dibayar = $data->sum('jumlah_belum_dibayar');
        $total_sudah_dibayar = $data->sum('jumlah_sudah_dibayar');
        $total_ditolak = $data->sum('jumlah_ditolak');
        $total_genteng_gajiblmdiambil = $data->sum('total_genteng_gajiblmdiambil');
        $total_genteng_gajidiambil = $data->sum('total_genteng_gajidiambil');
        $total_gaji = $data->sum('total_gaji');
        $total_gaji_diambil = $data->sum('total_gaji_diambil');

        // Ambil data pertama untuk digunakan di view
        $firstData = $data->first();

        // Membuat PDF dari view
        $pdf = PDF::loadView('pdf.laporan-karyawan', compact(
            'data',
            'total_pending_approval',
            'total_belum_dibayar',
            'total_sudah_dibayar',
            'total_ditolak',
            'total_genteng_gajiblmdiambil',
            'total_genteng_gajidiambil',
            'total_gaji',
            'total_gaji_diambil',
            'firstData',
            'startDateFormatted',
            'endDateFormatted'
        ))
            ->setPaper('a4', 'portrait'); // Mengatur ukuran kertas A4 dan orientasi portrait

        // Mengunduh file PDF
        return $pdf->download('laporan-' . $user->name . '.pdf');
    }

    // aman
    public function cetakSlipGaji($karyawanId)
    {
        $bulanIndo = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        $gajiInfo = session('gaji_info');

        if (!$gajiInfo) {
            return redirect()->route('admin.hasil-kerja.karyawan', [$karyawanId])
                ->with('error', 'Data gaji belum dihitung.');
        }

        $karyawan = User::findOrFail($karyawanId);

        // Dapatkan tanggal hari ini tanpa awalan nol menggunakan Carbon
        $tanggal = Carbon::now()->day; // Ambil tanggal
        $bulan = Carbon::now()->month; // Ambil bulan dalam angka (1-12)
        $tahun = Carbon::now()->year; // Ambil tahun

        // Ambil bulan dalam Bahasa Indonesia
        $bulanIndo = $bulanIndo[$bulan];  // Dapatkan nama bulan sesuai nomor bulan

        // Gabungkan menjadi format tanggal yang diinginkan
        $tanggalPengambilan = $tanggal . ' ' . $bulanIndo . ' ' . $tahun;

        // Kirim data ke view untuk cetak PDF
        $pdf = PDF::loadView('pdf.cetak-slip', compact('karyawan', 'gajiInfo', 'tanggalPengambilan'));

        // Download PDF dengan nama file berdasarkan nama karyawan
        return $pdf->download('slip_gaji_' . $karyawan->name . '.pdf');
    }
}
