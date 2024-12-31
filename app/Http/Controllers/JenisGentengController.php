<?php

namespace App\Http\Controllers;

use App\Models\JenisGenteng;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JenisGentengController extends Controller
{
    public function index()
    {
        $jenisGenteng = JenisGenteng::all();
        return view('admin.jenis-genteng', compact('jenisGenteng'));
    }

    public function jenis_genteng_add()
    {
        $jenisGenteng = JenisGenteng::all();
        return view('admin.jenis-genteng-add', compact('jenisGenteng'));
    }

    public function jenis_genteng_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_jenis' => 'required|string|max:255|unique:jenis_genteng,nama_jenis',
            'gaji_per_seribu' => 'required|integer|min:0',
        ], [
            'nama_jenis.required' => 'Nama jenis genteng harus diisi.',
            'nama_jenis.unique' => 'Nama jenis genteng sudah ada, harap pilih nama yang lain.',
            'gaji_per_seribu.required' => 'Gaji per seribu harus diisi.',
            'gaji_per_seribu.integer' => 'Gaji harus berupa angka.',
            'gaji_per_seribu.min' => 'Gaji tidak boleh negatif.',
        ]);

        if ($validator->fails()) {
            // Mengirim pesan error ke session menggunakan withErrors()
            return redirect()->route('admin.jenis-genteng.add')
                ->withErrors($validator)  // Mengirim semua pesan error validasi
                ->withInput();  // Mengirimkan input sebelumnya kembali ke form
        }

        // Menyimpan data jenis genteng baru jika tidak ada error
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
    $validator = Validator::make($request->all(), [
        'nama_jenis' => [
            'required',
            'string',
            'max:255',
            'unique:jenis_genteng,nama_jenis,' . $id // Unik, kecuali untuk ID yang sedang diupdate
        ],
        'gaji_per_seribu' => 'required|integer|min:0',
    ], [
        'nama_jenis.required' => 'Nama jenis genteng harus diisi.',
        'nama_jenis.unique' => 'Nama jenis genteng sudah ada, silakan pilih nama lain.',
        'gaji_per_seribu.required' => 'Gaji per seribu harus diisi.',
        'gaji_per_seribu.integer' => 'Gaji harus berupa angka.',
        'gaji_per_seribu.min' => 'Gaji tidak boleh negatif.',
    ]);

    if ($validator->fails()) {
        // Mengarahkan kembali dengan error
        return redirect()->route('admin.jenis-genteng.edit', ['id' => $id])
            ->withErrors($validator) // Mengirim error untuk ditampilkan di form
            ->withInput();  // Menyertakan input agar tetap terisi setelah gagal
    }

    // Menemukan jenis genteng berdasarkan ID
    $jenisGenteng = JenisGenteng::findOrFail($id);
    $jenisGenteng->nama_jenis = $request->nama_jenis;
    $jenisGenteng->gaji_per_seribu = $request->gaji_per_seribu;
    $jenisGenteng->save();

    // Mengirimkan pesan sukses
    return redirect()->route('admin.jenis-genteng.index')
        ->with('success', 'Jenis genteng berhasil diperbarui.');
}

    public function jenis_genteng_delete($id)
    {
        $jenisGenteng = JenisGenteng::findOrFail($id);

        $jenisGenteng->delete();

        return redirect()->route('admin.jenis-genteng.index')
            ->with('success', 'Jenis genteng berhasil dihapus.');
    }
}
