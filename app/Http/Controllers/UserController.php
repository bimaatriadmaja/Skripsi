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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $karyawan = Auth::user()->load('jenis_genteng');

        $jumlahPendingApproval = HasilKerja::where('user_id', $karyawan->id)
            ->where('status', 'pending')
            ->count();

        $jumlahBelumDibayar = HasilKerja::where('user_id', $karyawan->id)
            ->where('payment_status', 'unpaid')
            ->count();

        $jumlahGentengDisetujui = HasilKerja::where('user_id', $karyawan->id)
            ->where('status', 'approved')
            ->where('payment_status', 'unpaid')
            ->sum('jumlah_genteng');

        $jumlahHasilKerjaDitolak = HasilKerja::where('user_id', $karyawan->id)
            ->where('status', 'rejected')
            ->count();

        $gajiPerSeribu = $karyawan->jenis_genteng ? $karyawan->jenis_genteng->gaji_per_seribu : 0;

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


    //hasil kerja
    public function hasil_kerja()
    {
        $hasilKerja = HasilKerja::where('user_id', Auth::id())
            ->orderBy('tanggal_kerja', 'desc')
            ->paginate(5);
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
            'jumlah_genteng' => 'required|integer',
            'catatan' => 'nullable|string',
        ], [
            'tanggal_kerja.before_or_equal' => 'Tanggal kerja tidak boleh lebih dari hari ini.',
        ]);

        HasilKerja::create([
            'user_id' => Auth::id(),
            'tanggal_kerja' => $request->tanggal_kerja,
            'jumlah_genteng' => $request->jumlah_genteng,
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('user.hasil_kerja')->with('status', 'Hasil kerja berhasil disimpan!');
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
            'jumlah_genteng' => 'required|numeric',
            'catatan' => 'nullable|string',
        ], [
            'tanggal_kerja.before_or_equal' => 'Tanggal kerja tidak boleh lebih dari hari ini.',
        ]);

        $hasilKerja->update($validatedData);

        return redirect()->route('user.hasil_kerja')->with('status', 'Hasil kerja berhasil diperbarui.');
    }

    public function hasil_kerja_delete($id)
    {
        $hasilKerja = HasilKerja::findOrFail($id);
        $hasilKerja->delete();
        return redirect()->route('user.hasil_kerja')->with('status', 'Hasil kerja berhasil dihapus.');
    }

    // fitur search
    public function search(Request $request)
{
    $query = HasilKerja::where('user_id', Auth::id()); // Pastikan hanya data karyawan yang sedang login
    
    if ($search = $request->input('search')) {
        $query->where(function ($q) use ($search) {
            $q->where('tanggal_kerja', 'like', "%$search%")
                ->orWhere('jumlah_genteng', 'like', "%$search%")
                ->orWhere('catatan', 'like', "%$search%");

            // Daftar nama bulan dalam Bahasa Indonesia dan nomor bulan
            $bulanMap = [
                'januari' => 1, 'februari' => 2, 'maret' => 3, 'april' => 4,
                'mei' => 5, 'juni' => 6, 'juli' => 7, 'agustus' => 8,
                'september' => 9, 'oktober' => 10, 'november' => 11, 'desember' => 12
            ];

            // Ubah pencarian menjadi huruf kecil agar pencocokan tidak sensitif terhadap huruf besar/kecil
            $searchLower = strtolower($search);

            // Cek apakah nama bulan terdapat dalam inputan search (meskipun tidak lengkap)
            foreach ($bulanMap as $bulanName => $bulanNumber) {
                if (str_contains($bulanName, $searchLower)) {
                    $q->orWhereMonth('tanggal_kerja', $bulanNumber); // Filter berdasarkan bulan
                }
            }

            // Logika untuk status pembayaran
            if (stripos($search, 'belum dibayar') !== false) {
                $q->orWhere('payment_status', 'unpaid');
            } elseif (stripos($search, 'sudah dibayar') !== false) {
                $q->orWhere('payment_status', 'paid');
            }

            // Logika untuk status hasil kerja
            if (stripos($search, 'diproses') !== false) {
                $q->orWhere('status', 'pending');
            } elseif (stripos($search, 'disetujui') !== false) {
                $q->orWhere('status', 'approved');
            } elseif (stripos($search, 'ditolak') !== false) {
                $q->orWhere('status', 'rejected');
            }
        });
    }

    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    if ($startDate && $endDate) {
        $query->whereBetween('tanggal_kerja', [$startDate, $endDate]);
    } elseif ($startDate) {
        $query->where('tanggal_kerja', '>=', $startDate);
    } elseif ($endDate) {
        $query->where('tanggal_kerja', '<=', $endDate);
    }

    $hasilKerja = $query->paginate(10);

    return view('user.hasil-kerja', compact('hasilKerja'));
}

    
}    