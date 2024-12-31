<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\HasilKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $karyawan = Auth::user();
        $jenisGenteng = $karyawan->jenis_genteng;
        $gajiPerSeribu = $jenisGenteng ? $jenisGenteng->gaji_per_seribu : 0;

        $jumlahPendingApproval = HasilKerja::where('user_id', $karyawan->id)
            ->where('status', 'pending')
            ->count();

        $jumlahBelumDibayar = HasilKerja::where('user_id', $karyawan->id)
            ->where('status', 'approved')
            ->where('payment_status', 'unpaid')
            ->count();

        $jumlahGentengDisetujui = HasilKerja::where('user_id', $karyawan->id)
            ->where('status', 'approved')
            ->where('payment_status', 'unpaid')
            ->sum('jumlah_genteng');

        $jumlahHasilKerjaDitolak = HasilKerja::where('user_id', $karyawan->id)
            ->where('status', 'rejected')
            ->count();

        $totalGaji = ($jumlahGentengDisetujui * $gajiPerSeribu) / 1000;

        return view('user.index', compact(
            'karyawan',
            'jumlahPendingApproval',
            'jumlahBelumDibayar',
            'jumlahGentengDisetujui',
            'jumlahHasilKerjaDitolak',
            'totalGaji'
        ));
    }

    public function hasil_kerja()
    {
        $hasilKerja = HasilKerja::where('user_id', Auth::id())
            ->orderBy('tanggal_kerja', 'desc')
            ->paginate(5);

        foreach ($hasilKerja as $hasil) {
            $jenisGenteng = $hasil->user->jenis_genteng;
            if ($jenisGenteng) {
                $hasil->gaji = $jenisGenteng->gaji_per_seribu * ($hasil->jumlah_genteng / 1000);
            } else {
                $hasil->gaji = 0;
            }
        }

        return view('user.hasil-kerja', compact('hasilKerja'));
    }

    public function hasil_kerja_add()
    {
        return view('user.hasil-kerja-add');
    }

    public function storeHasilKerja(Request $request)
    {
        $request->validate([
            'tanggal_kerja' => 'required|date|date_format:Y-m-d|before_or_equal:' . Carbon::today()->toDateString(),
            'jumlah_genteng' => 'required|integer|min:1',
            'catatan' => 'nullable|string',
        ], [
            'tanggal_kerja.before_or_equal' => 'Tanggal kerja tidak boleh lebih dari hari ini.',
            'jumlah_genteng.min' => 'Jumlah genteng harus lebih besar dari 0.',
            'jumlah_genteng.integer' => 'Jumlah genteng harus berupa angka.',
        ]);

        HasilKerja::create([
            'user_id' => Auth::id(),
            'tanggal_kerja' => $request->tanggal_kerja,
            'jumlah_genteng' => $request->jumlah_genteng,
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('user.hasil_kerja')->with('success', 'Hasil kerja berhasil disimpan!');
    }

    public function hasil_kerja_edit($id)
    {
        $hasilKerja = HasilKerja::findOrFail($id);
        return view('user.hasil-kerja-edit', compact('hasilKerja'));
    }

    public function hasil_kerja_update(Request $request, $id)
    {
        $hasilKerja = HasilKerja::findOrFail($id);

        $today = Carbon::today()->toDateString();

        $validatedData = $request->validate([
            'tanggal_kerja' => 'required|date|date_format:Y-m-d|before_or_equal:' . $today,
            'jumlah_genteng' => 'required|numeric|min:1',
            'catatan' => 'nullable|string',
        ], [
            'tanggal_kerja.before_or_equal' => 'Tanggal kerja tidak boleh lebih dari hari ini.',
        ]);

        $hasilKerja->update($validatedData);

        return redirect()->route('user.hasil_kerja')->with('success', 'Hasil kerja berhasil diperbarui.');
    }

    public function hasil_kerja_delete($id)
    {
        $hasilKerja = HasilKerja::findOrFail($id);
        $hasilKerja->delete();
        return redirect()->route('user.hasil_kerja')->with('success', 'Hasil kerja berhasil dihapus.');
    }

    public function filter(Request $request)
{
    // Ambil data user yang sedang login
    $user = Auth::user();

    // Ambil input dari form filter
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    // Validasi tanggal (tanggal mulai dan tanggal akhir tidak boleh lebih dari hari ini)
    $today = date('Y-m-d');

    if (($startDate && !$endDate) || (!$startDate && $endDate)) {
        // Set session error jika hanya satu tanggal yang diisi
        return redirect()->back()->with('error', 'Anda harus mengisi tanggal mulai dan tanggal akhir.');
    }

    if ($startDate && strtotime($startDate) > strtotime($today)) {
        return redirect()->back()->with('error', 'Tanggal mulai tidak boleh lebih dari hari ini');
    }

    if ($endDate && strtotime($endDate) > strtotime($today)) {
        return redirect()->back()->with('error', 'Tanggal akhir tidak boleh lebih dari hari ini');
    }

    if ($startDate && $endDate && strtotime($startDate) > strtotime($endDate)) {
        return redirect()->back()->with('error', 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
    }

    // Query untuk filter berdasarkan hasil kerja
    $query = HasilKerja::where('user_id', $user->id);

    // Filter berdasarkan tanggal
    if ($startDate && $endDate) {
        $query->whereBetween('tanggal_kerja', [date('Y-m-d', strtotime($startDate)), date('Y-m-d', strtotime($endDate))]);
    } elseif ($startDate) {
        $query->where('tanggal_kerja', '>=', date('Y-m-d', strtotime($startDate)));
    } elseif ($endDate) {
        $query->where('tanggal_kerja', '<=', date('Y-m-d', strtotime($endDate)));
    }

    // Ambil hasil yang sudah difilter
    $allResults = $query->get();

    // Inisialisasi variabel total
    $totalGenteng = 0;
    $totalGajiPaid = 0;
    $totalGajiUnpaid = 0;

    // Hitung total untuk seluruh data yang sudah difilter
    foreach ($allResults as $hasil) {
        $jenisGenteng = $hasil->user->jenis_genteng;
        if ($jenisGenteng) {
            $gaji = $jenisGenteng->gaji_per_seribu * ($hasil->jumlah_genteng / 1000);
            $hasil->gaji = $gaji;

            if ($hasil->status == 'approved') {
                $totalGenteng += $hasil->jumlah_genteng;

                if ($hasil->payment_status === 'paid') {
                    $totalGajiPaid += $gaji;
                }

                if ($hasil->payment_status === 'unpaid') {
                    $totalGajiUnpaid += $gaji;
                }
            }
        } else {
            $hasil->gaji = 0;
        }
    }

    // Ambil hasil kerja dengan pagination
    $hasilKerja = $query->orderBy('tanggal_kerja', 'desc')->paginate(5);

    // Append parameter untuk pagination
    $hasilKerja->appends([
        'start_date' => $startDate,
        'end_date' => $endDate
    ]);

    // Data untuk laporan keseluruhan karyawan
    $laporanData = User::where('users.id', $user->id)
        ->leftJoin('hasil_kerja as hk', 'users.id', '=', 'hk.user_id')
        ->leftJoin('jenis_genteng as jg', 'users.jenis_genteng_id', '=', 'jg.id')
        ->select(
            'jg.nama_jenis',
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

    // Jika ada filter tanggal, terapkan pada laporan karyawan
    if ($startDate && $endDate) {
        $laporanData->whereBetween('hk.tanggal_kerja', [$startDate, $endDate]);
    } elseif ($startDate) {
        $laporanData->where('hk.tanggal_kerja', '>=', $startDate);
    } elseif ($endDate) {
        $laporanData->where('hk.tanggal_kerja', '<=', $endDate);
    }

    $laporan = $laporanData->get();

    // Total data untuk laporan karyawan
    $total_pending_approval = $laporan->sum('jumlah_pending_approval');
    $total_belum_dibayar = $laporan->sum('jumlah_belum_dibayar');
    $total_sudah_dibayar = $laporan->sum('jumlah_sudah_dibayar');
    $total_ditolak = $laporan->sum('jumlah_ditolak');
    $total_genteng_gajiblmdiambil = $laporan->sum('total_genteng_gajiblmdiambil');
    $total_genteng_gajidiambil = $laporan->sum('total_genteng_gajidiambil');
    $total_gaji = $laporan->sum('total_gaji');
    $total_gaji_diambil = $laporan->sum('total_gaji_diambil');

    // Return view dengan data filter dan laporan
    return view('user.hasil-kerja', compact(
        'hasilKerja',
        'totalGenteng',
        'totalGajiPaid',
        'totalGajiUnpaid',
        'startDate',
        'endDate',
        'laporan',
        'total_pending_approval',
        'total_belum_dibayar',
        'total_sudah_dibayar',
        'total_ditolak',
        'total_genteng_gajiblmdiambil',
        'total_genteng_gajidiambil',
        'total_gaji',
        'total_gaji_diambil'
    ));
}

}
