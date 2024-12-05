<?php

namespace App\Http\Controllers;

use App\Models\JenisGenteng; // Menambahkan import model JenisGenteng
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JenisGentengController extends Controller
{
    public function index()
    {
        $jenisGenteng = JenisGenteng::all(); // Ambil semua jenis genteng dari database
        return view('admin.jenis-genteng', compact('jenisGenteng'));
    }

    public function jenis_genteng_store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama_jenis' => 'required|string|max:255',
            'gaji_per_seribu' => 'required|integer|min:0',
        ], [
            'nama_jenis.required' => 'Nama jenis genteng harus diisi.',
            'gaji_per_seribu.required' => 'Gaji per seribu harus diisi.',
            'gaji_per_seribu.integer' => 'Gaji harus berupa angka.',
            'gaji_per_seribu.min' => 'Gaji tidak boleh negatif.',
        ]);
    
        if ($validator->fails()) {
            return redirect()->route('admin.jenis-genteng.index')
                ->withErrors($validator)
                ->withInput();
        }
    
        // Menambahkan jenis genteng baru
        $jenisGenteng = new JenisGenteng();
        $jenisGenteng->nama_jenis = $request->nama_jenis;
        $jenisGenteng->gaji_per_seribu = $request->gaji_per_seribu;
        $jenisGenteng->save();
    
        return redirect()->route('admin.jenis-genteng.index')
            ->with('success', 'Jenis genteng berhasil ditambahkan.');
    }

    public function jenis_genteng_edit($id)
{
    $jenisGenteng = JenisGenteng::findOrFail($id); // Ambil data jenis genteng berdasarkan ID
    return view('admin.jenis-genteng-edit', compact('jenisGenteng'));
}
    
    public function jenis_genteng_update(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama_jenis' => 'required|string|max:255',
            'gaji_per_seribu' => 'required|integer|min:0',
        ], [
            'nama_jenis.required' => 'Nama jenis genteng harus diisi.',
            'gaji_per_seribu.required' => 'Gaji per seribu harus diisi.',
            'gaji_per_seribu.integer' => 'Gaji harus berupa angka.',
            'gaji_per_seribu.min' => 'Gaji tidak boleh negatif.',
        ]);
    
        if ($validator->fails()) {
            return redirect()->route('admin.jenis-genteng.index')
                ->withErrors($validator)
                ->withInput();
        }
    
        // Temukan jenis genteng berdasarkan ID
        $jenisGenteng = JenisGenteng::findOrFail($id);
        $jenisGenteng->nama_jenis = $request->nama_jenis;
        $jenisGenteng->gaji_per_seribu = $request->gaji_per_seribu;
        $jenisGenteng->save();
    
        return redirect()->route('admin.jenis-genteng.index')
            ->with('success', 'Jenis genteng berhasil diperbarui.');
    }
    
    public function jenis_genteng_delete($id)
    {
        $jenisGenteng = JenisGenteng::findOrFail($id);
    
        // Menghapus jenis genteng
        $jenisGenteng->delete();
    
        return redirect()->route('admin.jenis-genteng.index')
            ->with('success', 'Jenis genteng berhasil dihapus.');
    }
    
}
