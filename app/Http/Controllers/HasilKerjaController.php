<?php

namespace App\Http\Controllers;

use App\Models\HasilKerja;
use App\Models\JenisGenteng;
use App\Models\User;
use Illuminate\Http\Request;

class HasilKerjaController extends Controller
{

    public function edit($id)
    {
        $hasilKerja = HasilKerja::findOrFail($id);
        return view('admin.hasil-kerja-kar-status', compact('hasilKerja'));
    }
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'payment_status' => 'required|in:unpaid,paid',
        ]);

        $hasilKerja = HasilKerja::findOrFail($id);

        $hasilKerja->status = $request->status;
        $hasilKerja->payment_status = $request->payment_status;
        $hasilKerja->save();

        return redirect()->route('admin.hasil-kerja.karyawan', ['user_id' => $hasilKerja->user_id])
            ->with('success', 'Status berhasil diperbarui.');
    }

    public function showHasilKerjaKaryawan($user_id)
    {
        $karyawan = User::findOrFail($user_id);
        $jenisGenteng = JenisGenteng::all();

        return view('admin.hasil-kerja.karyawan', compact('karyawan', 'jenisGenteng'));
    }

    public function updateJenisGenteng(Request $request, $user_id)
    {
        $request->validate([
            'jenis_genteng_id' => 'required|exists:jenis_gentengs,id', // Validasi ID jenis genteng
        ]);

        $karyawan = User::findOrFail($user_id);

        $karyawan->jenis_genteng_id = $request->jenis_genteng_id;
        $karyawan->save();

        return redirect()->route('admin.hasil-kerja.karyawan', $user_id)
            ->with('success', 'Jenis genteng berhasil diperbarui!');
    }
}
