<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\HasilKerja;
use App\Models\JenisGenteng;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Laravel\Facades\Image;

class AdminController extends Controller
{
    public function index()
{
    // Mengambil data hasil kerja yang menunggu approval, belum dibayar, dan ditolak
    $data = User::where('utype', 'USR') // Ambil hanya karyawan
        ->leftJoin('hasil_kerja as hk', 'users.id', '=', 'hk.user_id')
        ->select('users.id', 'users.name as karyawan_name', 
            DB::raw('COUNT(CASE WHEN hk.status = "pending" THEN 1 END) as jumlah_pending_approval'),
            DB::raw('COUNT(CASE WHEN hk.payment_status = "unpaid" THEN 1 END) as jumlah_belum_dibayar'),
            DB::raw('COUNT(CASE WHEN hk.status = "rejected" THEN 1 END) as jumlah_ditolak') // Tambahkan ini untuk menghitung yang ditolak
        )
        ->groupBy('users.id')
        ->get();

    // Mengirim data ke view
    return view('admin.index', compact('data'));
}


    // Fungsi untuk menampilkan hasil kerja masing-masing karyawan
    public function lihatHasilfromIndex($user_id)
    {
        $karyawan = User::findOrFail($user_id);
        $hasilKerja = HasilKerja::where('user_id', $user_id)->get();
        return view('admin.hasil-kerja', compact('karyawan', 'hasilKerja'));
    }


    //Melihat user
    public function karyawan()
    {
        // Ambil data karyawan dengan pagination, misalnya 10 data per halaman
        $karyawan = User::where('utype', 'USR')->with('jenis_genteng')->paginate(10);
        $jenisGenteng = JenisGenteng::all();
        // Tampilkan ke view
        return view('admin.karyawan', compact('karyawan','jenisGenteng'));
    }

    public function karyawan_add()
    {
        $jenisGenteng = JenisGenteng::all();
        return view('admin.karyawan-add', compact('jenisGenteng'));
    }

    // Menyimpan data karyawan baru
    public function karyawan_store(Request $request)
{
    // Validasi input
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'mobile' => 'required|string|max:15|unique:users,mobile',
        'password' => 'required|confirmed|min:8', // Password wajib diisi dan harus terkonfirmasi
        'jenis_genteng_id' => 'nullable|exists:jenis_genteng,id', // Pastikan jenis_genteng_id valid (nullable)
    ], [
        'name.required' => 'Nama harus diisi.',
        'email.required' => 'Email harus diisi.',
        'email.email' => 'Format email tidak valid.',
        'email.unique' => 'Email sudah terdaftar.',
        'mobile.required' => 'Nomor HP harus diisi.',
        'mobile.unique' => 'Nomor HP sudah terdaftar untuk pengguna lain.',
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

    // Menambahkan karyawan baru
    $karyawan = new User();
    $karyawan->name = $request->name;
    $karyawan->email = $request->email;
    $karyawan->mobile = $request->mobile;
    $karyawan->jenis_genteng_id = $request->jenis_genteng_id; // Menyimpan jenis genteng yang dipilih
    
    // Menyimpan password yang ter-hash
    $karyawan->password = Hash::make($request->password);
    
    // Menyimpan data karyawan
    $karyawan->save();

    return redirect()->route('admin.karyawan')
        ->with('success', 'Karyawan baru berhasil ditambahkan.');
}


    public function karyawan_edit($id)
    {
        $karyawan = User::findOrFail($id);
        $jenisGenteng = JenisGenteng::all(); // Mengambil semua jenis genteng dari tabel jenis_genteng
        return view('admin.karyawan-edit', compact('karyawan', 'jenisGenteng'));
    }

    public function karyawan_update(Request $request, $id)
{
    // Validasi input
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id,
        'mobile' => 'required|string|max:15|unique:users,mobile,' . $id, // Pastikan nomor HP unik
        'password' => 'nullable|confirmed|min:8',
        'old_password' => 'nullable|current_password', // Tambahkan validasi untuk old_password
    ], [
        'name.required' => 'Nama harus diisi.',
        'email.required' => 'Email harus diisi.',
        'email.email' => 'Format email tidak valid.',
        'email.unique' => 'Email sudah terdaftar.',
        'mobile.required' => 'Nomor HP harus diisi.',
        'mobile.unique' => 'Nomor HP sudah terdaftar untuk pengguna lain.',
        'old_password.required' => 'Kata sandi lama harus diisi jika ingin mengganti kata sandi.',
        'old_password.current_password' => 'Kata sandi saat ini salah.',
        'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        'password.min' => 'Kata sandi minimal harus 8 karakter.',
    ]);

    if ($validator->fails()) {
        return redirect()->route('admin.karyawan.edit', $id)
            ->withErrors($validator)
            ->withInput();
    }

    // Temukan karyawan berdasarkan ID
    $karyawan = User::findOrFail($id);
    $karyawan->name = $request->name;
    $karyawan->email = $request->email;
    $karyawan->mobile = $request->mobile;

    // Hanya update password jika diisi
    if ($request->filled('password')) {
        if (!$request->filled('old_password')) {
            return redirect()->route('admin.karyawan.edit', $id)
                ->withErrors(['old_password.required' => 'Kata sandi lama harus diisi untuk mengganti kata sandi.'])
                ->withInput();
        }

        $karyawan->password = Hash::make($request->password);
    }

    // Update jenis genteng jika ada
    if ($request->filled('jenis_genteng_id')) {
        $karyawan->jenis_genteng_id = $request->jenis_genteng_id;
    } else {
        // Jika tidak ada, set jenis genteng_id menjadi null
        $karyawan->jenis_genteng_id = null;
    }

    $karyawan->save();

    return redirect()->route('admin.karyawan')
        ->with('success', 'Data karyawan berhasil diperbarui.');
}



    public function karyawan_delete($id)
    {
        $karyawan = User::findOrFail($id);

        // Menghapus karyawan
        $karyawan->delete();

        return redirect()->route('admin.karyawan')->with('success', 'Karyawan berhasil dihapus.');
    }

    public function hasilKerjaByKaryawan(Request $request, $user_id)
{
    if (Auth::user()->utype !== 'ADM') {
        return redirect()->route('home')->with('error', 'Anda tidak memiliki akses');
    }

    $karyawan = User::findOrFail($user_id);

    // Inisialisasi filter
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    // Query untuk mengambil hasil kerja berdasarkan user
    $query = HasilKerja::where('user_id', $user_id);

    // Filter berdasarkan rentang tanggal jika ada
    if ($startDate && $endDate) {
        $query->whereBetween('tanggal_kerja', [$startDate, $endDate]);
    } elseif ($startDate) {
        $query->where('tanggal_kerja', '>=', $startDate);
    } elseif ($endDate) {
        $query->where('tanggal_kerja', '<=', $endDate);
    }

    // Urutkan data berdasarkan tanggal kerja dari yang terbaru
    $query->orderBy('tanggal_kerja', 'desc');

    // Ambil semua hasil kerja sesuai filter
    $hasilKerja = $query->paginate(10);

    return view('admin.hasil-kerja-kar', compact('karyawan', 'hasilKerja'));
}



    public function updatePaymentStatus(Request $request)
    {
        if (Auth::user()->utype !== 'ADM') {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses');
        }

        $userId = $request->input('user_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $statusFilter = $request->input('status');

        // Validasi: Pastikan tanggal mulai dan tanggal akhir ada jika status filter diterapkan
        if (!$startDate || !$endDate) {
            return redirect()->route('admin.hasil-kerja.karyawan', $userId)
                ->with('error', 'Harus filter tanggal dulu');
        }

        $query = HasilKerja::where('user_id', $userId)
            ->where('status', 'approved');

        // Filter berdasarkan rentang tanggal jika ada
        if ($startDate && $endDate) {
            $query->whereBetween('tanggal_kerja', [$startDate, $endDate]);
        }

        // Update payment_status menjadi 'paid'
        $query->update(['payment_status' => 'paid']);

        return redirect()->route('admin.hasil-kerja.karyawan', $userId)->with('success', 'Payment status updated for all approved results.');
    }

    public function setJenisGenteng(Request $request, $id)
    {
        $request->validate([
            'jenis_genteng_id' => 'nullable|exists:jenis_genteng,id',
        ]);

        $karyawan = User::findOrFail($id);
        $karyawan->jenis_genteng_id = $request->jenis_genteng_id;
        $karyawan->save();

        return redirect()->route('admin.karyawan')->with('success', 'Jenis genteng berhasil diperbarui.');
    }


    public function hitungGajiKaryawan($karyawanId)
    {
        $karyawan = User::findOrFail($karyawanId);
        $jenisGenteng = $karyawan->jenis_genteng; // Perbaikan nama relasi
    
        if (!$jenisGenteng) {
            return response()->json(['success' => false, 'message' => 'Jenis genteng belum diatur untuk karyawan ini.']);
        }
    
        $hasilKerja = HasilKerja::where('user_id', $karyawanId)
            ->where('status', 'approved')
            ->where('payment_status', 'unpaid')
            ->get();
        
        // Tentukan periode rentang waktu
        $startDate = $hasilKerja->min('tanggal_kerja'); // Ambil tanggal kerja pertama
        $endDate = $hasilKerja->max('tanggal_kerja'); // Ambil tanggal kerja terakhir
    
        $totalGaji = 0;
        $totalGenteng = 0;
    
        foreach ($hasilKerja as $kerja) {
            $totalGaji += ($kerja->jumlah_genteng / 1000) * $jenisGenteng->gaji_per_seribu;
            $totalGenteng += $kerja->jumlah_genteng;
        }
    
        $totalGajiFormatted = number_format($totalGaji, 0, ',', '.');
    
        return response()->json([
            'success' => true,
            'totalGaji' => $totalGaji,
            'totalGajiFormatted' => $totalGajiFormatted,
            'totalGenteng' => $totalGenteng,
            'startDate' => \Carbon\Carbon::parse($startDate)->format('Y-m-d'),
            'endDate' => \Carbon\Carbon::parse($endDate)->format('Y-m-d'),
        ]);
    }
    

    public function markAsPaid(Request $request)
    {
        $userId = $request->input('user_id');
        $user = User::find($userId);
    
        if (!$user) {
            return response()->json(['message' => 'User not found!'], 404);
        }
    
        $hasilKerja = HasilKerja::where('user_id', $userId)
                                ->where('status', 'approved')
                                ->where('payment_status', 'unpaid')
                                ->get();
    
        if ($hasilKerja->isEmpty()) {
            return response()->json(['message' => 'Tidak ada hasil kerja yang dapat dibayar.'], 400);
        }
    
        foreach ($hasilKerja as $kerja) {
            $kerja->payment_status = 'paid';
            $kerja->save();
        }
    
        // Mengirim pesan sukses ke session
        session()->flash('success', 'Pembayaran berhasil diperbarui.');
    
        return response()->json(['message' => 'Pembayaran berhasil diperbarui.']);
    }
    
}
