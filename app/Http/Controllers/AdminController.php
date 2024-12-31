<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\HasilKerja;
use App\Models\JenisGenteng;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    // Seputar Dashboard
    public function index()
    {
        $data = User::where('utype', 'USR')
            ->leftJoin('hasil_kerja as hk', 'users.id', '=', 'hk.user_id')
            ->leftJoin('jenis_genteng as jg', 'users.jenis_genteng_id', '=', 'jg.id')
            ->select(
                'users.id',
                'users.name as karyawan_name',
                DB::raw('COUNT(CASE WHEN hk.status = "pending" THEN 1 END) as jumlah_pending_approval'),
                DB::raw('COUNT(CASE WHEN hk.payment_status = "unpaid" AND hk.status = "approved" THEN 1 END) as jumlah_belum_dibayar'),
                DB::raw('COUNT(CASE WHEN hk.status = "rejected" THEN 1 END) as jumlah_ditolak'),
                DB::raw('SUM(CASE WHEN hk.status = "approved" AND hk.payment_status = "unpaid" THEN hk.jumlah_genteng ELSE 0 END) as total_genteng_disetujui'),
                DB::raw('SUM(CASE WHEN hk.status = "approved" AND hk.payment_status = "unpaid" THEN hk.jumlah_genteng * jg.gaji_per_seribu / 1000 ELSE 0 END) as total_gaji')
            )
            ->groupBy('users.id', 'users.name')
            ->get();

        $total_pending_approval = $data->sum('jumlah_pending_approval');
        $total_belum_dibayar = $data->sum('jumlah_belum_dibayar');
        $total_ditolak = $data->sum('jumlah_ditolak');
        $total_genteng_disetujui = $data->sum('total_genteng_disetujui');
        $total_gaji = $data->sum('total_gaji');

        return view('admin.index', compact('data', 'total_pending_approval', 'total_belum_dibayar', 'total_ditolak', 'total_genteng_disetujui', 'total_gaji'));
    }

    public function showApproval()
    {
        $karyawans = User::where('utype', 'USR')
            ->leftJoin('hasil_kerja as hk', 'users.id', '=', 'hk.user_id')
            ->select('users.id', 'users.name as karyawan_name', DB::raw('COUNT(CASE WHEN hk.status = "pending" THEN 1 END) as jumlah_pending_approval'))
            ->groupBy('users.id', 'users.name')
            ->having('jumlah_pending_approval', '>', 0)
            ->get();

        return view('admin.index-approval', compact('karyawans'));
    }

    public function showBelumDibayar()
    {
        $karyawans = User::where('utype', 'USR')
            ->leftJoin('hasil_kerja as hk', 'users.id', '=', 'hk.user_id')
            ->select('users.id', 'users.name as karyawan_name', DB::raw('COUNT(CASE WHEN hk.payment_status = "unpaid" AND hk.status = "approved" THEN 1 END) as jumlah_belum_dibayar'))
            ->groupBy('users.id', 'users.name')
            ->having('jumlah_belum_dibayar', '>', 0)
            ->get();

        return view('admin.index-belum-dibayar', compact('karyawans'));
    }

    public function showDitolak()
    {
        $karyawans = User::where('utype', 'USR')
            ->leftJoin('hasil_kerja as hk', 'users.id', '=', 'hk.user_id')
            ->select('users.id', 'users.name as karyawan_name', DB::raw('COUNT(CASE WHEN hk.status = "rejected" THEN 1 END) as jumlah_ditolak'))
            ->groupBy('users.id', 'users.name')
            ->having('jumlah_ditolak', '>', 0)
            ->get();

        return view('admin.index-ditolak', compact('karyawans'));
    }

    public function showGenteng()
    {
        $karyawans = User::where('utype', 'USR')
            ->leftJoin('hasil_kerja as hk', 'users.id', '=', 'hk.user_id')
            ->leftJoin('jenis_genteng as jg', 'users.jenis_genteng_id', '=', 'jg.id')
            ->select(
                'users.id',
                'users.name as karyawan_name',
                DB::raw('SUM(CASE WHEN hk.status = "approved" AND hk.payment_status = "unpaid" THEN hk.jumlah_genteng ELSE 0 END) as total_genteng_disetujui'),
                DB::raw('SUM(CASE WHEN hk.status = "approved" AND hk.payment_status = "unpaid" THEN hk.jumlah_genteng * jg.gaji_per_seribu / 1000 ELSE 0 END) as total_gaji')
            )
            ->groupBy('users.id', 'users.name')
            ->having('total_genteng_disetujui', '>', 0)
            ->get();

        return view('admin.index-genteng', compact('karyawans'));
    }


    public function showGaji()
    {
        $karyawans = User::where('utype', 'USR')
            ->leftJoin('hasil_kerja as hk', 'users.id', '=', 'hk.user_id')
            ->leftJoin('jenis_genteng as jg', 'users.jenis_genteng_id', '=', 'jg.id')
            ->select(
                'users.id',
                'users.name as karyawan_name',
                DB::raw('SUM(CASE WHEN hk.status = "approved" AND hk.payment_status = "unpaid" THEN hk.jumlah_genteng * jg.gaji_per_seribu / 1000 ELSE 0 END) as total_gaji')
            )
            ->groupBy('users.id', 'users.name')
            ->having('total_gaji', '>', 0)
            ->get();

        return view('admin.index-gaji', compact('karyawans'));
    }

    // Sidebar Karyawan
    public function karyawan()
    {
        $karyawan = User::where('utype', 'USR')->with('jenis_genteng')->paginate(5);
        $jenisGenteng = JenisGenteng::all();

        return view('admin.karyawan', compact('karyawan', 'jenisGenteng'));
    }

    public function karyawan_add()
    {
        $jenisGenteng = JenisGenteng::all();
        return view('admin.karyawan-add', compact('jenisGenteng'));
    }

    public function karyawan_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|numeric|digits_between:10,15|unique:users,mobile',
            'password' => 'required|confirmed|min:8',
            'jenis_genteng_id' => 'nullable|exists:jenis_genteng,id',
        ], [
            'name.required' => 'Nama harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar untuk pengguna lain.',
            'mobile.required' => 'Nomor HP harus diisi.',
            'mobile.unique' => 'Nomor HP sudah terdaftar untuk pengguna lain.',
            'mobile.digits_between' => 'Nomor HP harus memiliki panjang antara 10 hingga 15 karakter.',
            'password.required' => 'Password harus diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal harus 8 karakter.',
            'jenis_genteng_id.exists' => 'Jenis genteng yang dipilih tidak valid.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.karyawan.add')
                ->withErrors($validator)
                ->withInput();
        }

        $karyawan = new User();
        $karyawan->name = $request->name;
        $karyawan->email = $request->email;
        $karyawan->mobile = $request->mobile;
        $karyawan->jenis_genteng_id = $request->jenis_genteng_id;

        $karyawan->password = Hash::make($request->password);

        $karyawan->save();

        return redirect()->route('admin.karyawan')
            ->with('success', 'Karyawan baru berhasil ditambahkan.');
    }

    public function karyawan_edit($id)
    {
        $karyawan = User::findOrFail($id);
        $jenisGenteng = JenisGenteng::all();
        return view('admin.karyawan-edit', compact('karyawan', 'jenisGenteng'));
    }

    public function karyawan_update(Request $request, $id)
    {
        // Validasi data yang diterima dari form
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'mobile' => 'required|numeric|digits_between:10,15|unique:users,mobile,' . $id,
            'password' => 'nullable|confirmed|min:8',
            'password_confirmation' => 'nullable|same:password',
        ], [
            'name.required' => 'Nama harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar untuk pengguna lain.',
            'mobile.required' => 'Nomor HP harus diisi.',
            'mobile.unique' => 'Nomor HP sudah terdaftar untuk pengguna lain.',
            'mobile.digits_between' => 'Nomor HP harus memiliki panjang antara 10 hingga 15 karakter.',
            'password.min' => 'Kata sandi minimal harus 8 karakter.',
            'password_confirmation.same' => 'Password konfirmasi tidak cocok.',
        ]);

        if ($request->filled('password') && !$request->filled('password_confirmation')) {
            $validator->after(function ($validator) {
                $validator->errors()->add('password_confirmation', 'Konfirmasi kata sandi harus diisi.');
            });
        }

        if (!$request->filled('password') && $request->filled('password_confirmation')) {
            $validator->after(function ($validator) {
                $validator->errors()->add('password', 'Kata sandi harus diisi dulu sebelum konfirmasi.');
            });
        }

        if ($validator->fails()) {
            return redirect()->route('admin.karyawan.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        $karyawan = User::findOrFail($id);
        $karyawan->name = $request->name;
        $karyawan->email = $request->email;
        $karyawan->mobile = $request->mobile;

        if ($request->filled('password')) {
            $karyawan->password = Hash::make($request->password);
        }

        if ($request->filled('jenis_genteng_id')) {
            $karyawan->jenis_genteng_id = $request->jenis_genteng_id;
        } else {
            $karyawan->jenis_genteng_id = null;
        }
        $karyawan->save();

        return redirect()->route('admin.karyawan')
            ->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function karyawan_delete($id)
    {
        $karyawan = User::findOrFail($id);
        $karyawan->delete();

        return redirect()->route('admin.karyawan')->with('success', 'Karyawan berhasil dihapus.');
    }

    // sidebar hasil kerja
    public function hasilkerjaSidebar()
    {
        $karyawan = User::where('utype', 'USR')->with('jenis_genteng')->paginate(6);
        $jenisGenteng = JenisGenteng::all();

        return view('admin.data-hasil-kerja', compact('karyawan', 'jenisGenteng'));
    }

    public function hasilKerjaByKaryawan(Request $request, $karyawanId)
    {
        // Ambil data karyawan
        $karyawan = User::findOrFail($karyawanId);

        // Ambil input dari form filter
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Validasi tanggal (tanggal mulai dan tanggal akhir tidak boleh lebih dari hari ini)
        $today = date('Y-m-d');

        // Validasi jika salah satu tanggal tidak diisi
        if (($startDate && !$endDate) || (!$startDate && $endDate)) {
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
        $query = HasilKerja::where('user_id', $karyawan->id);

        // Filter berdasarkan tanggal
        if ($startDate && $endDate) {
            $query->whereBetween('tanggal_kerja', [date('Y-m-d', strtotime($startDate)), date('Y-m-d', strtotime($endDate))]);
        } elseif ($startDate) {
            $query->where('tanggal_kerja', '>=', date('Y-m-d', strtotime($startDate)));
        } elseif ($endDate) {
            $query->where('tanggal_kerja', '<=', date('Y-m-d', strtotime($endDate)));
        }

        // Ambil hasil kerja dengan pagination dan tambahkan filter ke URL pagination
        $hasilKerja = $query->orderBy('tanggal_kerja', 'desc')->paginate(5);

        // Menambahkan parameter filter ke URL pagination
        $hasilKerja->appends([
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        // Hitung total untuk seluruh data yang sudah difilter
        $totalGenteng = 0;
        $totalGajiPaid = 0;
        $totalGajiUnpaid = 0;
        $total_pending_approval = 0;
        $total_belum_dibayar = 0;
        $total_sudah_dibayar = 0;
        $total_ditolak = 0;
        $total_genteng_gajiblmdiambil = 0;
        $total_genteng_gajidiambil = 0;
        $total_gaji = 0;
        $total_gaji_diambil = 0;

        foreach ($hasilKerja as $hasil) {
            $jenisGenteng = $hasil->user->jenis_genteng;
            if ($jenisGenteng) {
                $gaji = $jenisGenteng->gaji_per_seribu * ($hasil->jumlah_genteng / 1000);
                $hasil->gaji = $gaji;

                if ($hasil->status == 'approved') {
                    $totalGenteng += $hasil->jumlah_genteng;

                    if ($hasil->payment_status === 'paid') {
                        $totalGajiPaid += $gaji;
                        $total_gaji_diambil += $gaji;
                        $total_genteng_gajidiambil += $hasil->jumlah_genteng;
                    }

                    if ($hasil->payment_status === 'unpaid') {
                        $totalGajiUnpaid += $gaji;
                        $total_gaji += $gaji;
                        $total_genteng_gajiblmdiambil += $hasil->jumlah_genteng;
                    }
                }

                // Hitung total berdasarkan status
                if ($hasil->status == 'pending') {
                    $total_pending_approval++;
                } elseif ($hasil->status == 'rejected') {
                    $total_ditolak++;
                } elseif ($hasil->payment_status == 'unpaid') {
                    $total_belum_dibayar++;
                } elseif ($hasil->payment_status == 'paid') {
                    $total_sudah_dibayar++;
                }
            } else {
                $hasil->gaji = 0;
            }
        }

        // Data untuk laporan keseluruhan karyawan
        $laporanData = User::where('users.id', $karyawan->id)
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

        // Kirim data ke view
        return view('admin.hasil-kerja-kar', compact(
            'karyawan',
            'hasilKerja',
            'total_pending_approval',
            'total_belum_dibayar',
            'total_sudah_dibayar',
            'total_ditolak',
            'total_genteng_gajiblmdiambil',
            'total_genteng_gajidiambil',
            'total_gaji',
            'total_gaji_diambil',
            'startDate',
            'endDate',
            'laporan'
        ));
    }

    public function hitungGaji(Request $request, $karyawanId)
    {
        $bulanIndo = [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];

        $karyawan = User::findOrFail($karyawanId);
        $jenisGenteng = $karyawan->jenis_genteng;
        $gajiPerSeribu = $jenisGenteng ? $jenisGenteng->gaji_per_seribu : 0;

        // Cek apakah jenis genteng dan gaji per seribu ada
        if (!$jenisGenteng || !$gajiPerSeribu) {
            return response()->json([
                'error' => 'Jenis genteng dan gaji per seribu belum ditentukan'
            ], 400);
        }
        $hasilKerja = DB::table('hasil_kerja')
            ->where('user_id', $karyawanId)
            ->where('status', 'approved')
            ->where('payment_status', 'unpaid')
            ->get();

        if ($hasilKerja->isEmpty()) {
            return response()->json([
                'error' => 'Tidak ada data yang sudah disetujui & belum diambil gajinya'
            ], 400);
        }

        $startDate = Carbon::parse($hasilKerja->min('tanggal_kerja'));
        $endDate = Carbon::parse($hasilKerja->max('tanggal_kerja'));

        $startDay = $startDate->day;
        $startMonth = $bulanIndo[$startDate->month - 1];
        $startYear = $startDate->year;

        $endDay = $endDate->day;
        $endMonth = $bulanIndo[$endDate->month - 1];
        $endYear = $endDate->year;

        $periode = $startDay . ' ' . $startMonth . ' ' . $startYear . ' - ' . $endDay . ' ' . $endMonth . ' ' . $endYear;

        $totalGenteng = $hasilKerja->sum('jumlah_genteng');
        $totalGaji = ($totalGenteng / 1000) * $gajiPerSeribu;

        // Simpan data ke session untuk digunakan nanti pada cetakSlipGaji
        session([
            'gaji_info' => [
                'jenisGenteng' => $jenisGenteng ? $jenisGenteng->nama_jenis : 'Tidak Diketahui',
                'gajiPerSeribu' => number_format($gajiPerSeribu, 0, ',', '.'),
                'totalGenteng' => number_format($totalGenteng, 0, ',', '.'),
                'totalGaji' => 'Rp ' . number_format($totalGaji, 0, ',', '.'),
                'periode' => $periode,
            ]
        ]);

        return response()->json([
            'jenisGenteng' => $jenisGenteng ? $jenisGenteng->nama_jenis : 'Tidak Diketahui',
            'gajiPerSeribu' => number_format($gajiPerSeribu, 0, ',', '.'),
            'totalGenteng' => number_format($totalGenteng, 0, ',', '.'),
            'totalGaji' => 'Rp ' . number_format($totalGaji, 0, ',', '.'),
            'periode' => $periode,
        ]);
    }

    public function markAsPaid(Request $request)
    {
        $user_id = $request->input('user_id');

        if (!$user_id) {
            return redirect()->back()->with('error', 'User ID tidak ditemukan.');
        }

        $hasilKerja = HasilKerja::where('user_id', $user_id)
            ->where('payment_status', 'unpaid')
            ->where('status', 'approved')
            ->update(['payment_status' => 'paid']);

        if ($hasilKerja) {
            return redirect()->route('admin.hasil-kerja.karyawan', [$user_id])
                ->with('status', 'Semua hasil kerja berhasil ditandai sebagai dibayar.');
        } else {
            return redirect()->route('admin.hasil-kerja.karyawan', [$user_id])
                ->with('error', 'Tidak ada hasil kerja yang perlu ditandai sebagai dibayar.');
        }
    }

    // cetak laporan keseluruhan
    public function laporan_index(Request $request)
    {
        if (Auth::user()->utype !== 'ADM') {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses');
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $today = Carbon::today();

        if ($startDate && strtotime($startDate) > strtotime($today)) {
            return redirect()->route('admin.laporan-keseluruhan.index')->with('error', 'Tanggal mulai tidak boleh lebih dari hari ini');
        }

        if ($endDate && strtotime($endDate) > strtotime($today)) {
            return redirect()->route('admin.laporan-keseluruhan.index')->with('error', 'Tanggal akhir tidak boleh lebih dari hari ini');
        }

        if ($startDate && $endDate && strtotime($startDate) > strtotime($endDate)) {
            return redirect()->route('admin.laporan-keseluruhan.index')->with('error', 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
        }
        // Validasi hanya satu tanggal
        if ($startDate && !$endDate) {
            return redirect()->route('admin.laporan-keseluruhan.index')->with('error', 'Tanggal akhir harus diisi.');
        }

        if (!$startDate && $endDate) {
            return redirect()->route('admin.laporan-keseluruhan.index')->with('error', 'Tanggal mulai harus diisi.');
        }


        $query = User::where('utype', 'USR')
            ->leftJoin('hasil_kerja as hk', 'users.id', '=', 'hk.user_id')
            ->leftJoin('jenis_genteng as jg', 'users.jenis_genteng_id', '=', 'jg.id')
            ->select(
                'users.id',
                'users.name as karyawan_name',
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
            ->groupBy('users.id', 'users.name', 'jg.nama_jenis', 'jg.gaji_per_seribu');

        if ($startDate && $endDate) {
            $query->whereBetween('hk.tanggal_kerja', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->where('hk.tanggal_kerja', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('hk.tanggal_kerja', '<=', $endDate);
        }

        $data = $query->get();

        $total_pending_approval = $data->sum('jumlah_pending_approval');
        $total_belum_dibayar = $data->sum('jumlah_belum_dibayar');
        $total_sudah_dibayar = $data->sum('jumlah_sudah_dibayar');
        $total_ditolak = $data->sum('jumlah_ditolak');
        $total_genteng_gajiblmdiambil = $data->sum('total_genteng_gajiblmdiambil');
        $total_genteng_gajidiambil = $data->sum('total_genteng_gajidiambil');
        $total_gaji = $data->sum('total_gaji');
        $total_gaji_diambil = $data->sum('total_gaji_diambil');

        return view('admin.laporan-keseluruhan', compact(
            'data',
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
