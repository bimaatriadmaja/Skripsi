<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\HasilKerja;
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

        if ($request->payment_status === 'paid' && $hasilKerja->status !== 'approved') {
            return redirect()->route('admin.hasil-kerja.karyawan', ['user_id' => $hasilKerja->user_id])
                ->with('error', 'Pembayaran hanya bisa dilakukan jika hasil kerja sudah disetujui.');
        }

        if (in_array($request->status, ['pending', 'rejected'])) {
            $request->merge(['payment_status' => 'unpaid']);
        }

        $hasilKerja->status = $request->status;
        $hasilKerja->payment_status = $request->payment_status;
        $hasilKerja->save();

        return redirect()->route('admin.hasil-kerja.karyawan', ['user_id' => $hasilKerja->user_id])
            ->with('success', 'Status berhasil diperbarui.');
    }

    public function checkHasilKerja($user_id)
    {
        $karyawan = User::findOrFail($user_id);

        $hasResults = HasilKerja::where('user_id', $user_id)->exists();

        if (!$hasResults) {
            return redirect()->route('admin.hasil-kerja-sidebar')->with('error', 'Belum ada hasil kerja yang ditambahkan oleh karyawan ' . $karyawan->name);
        }

        return redirect()->route('admin.hasil-kerja.karyawan', $user_id);
    }
}
