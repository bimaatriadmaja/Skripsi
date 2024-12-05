<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('user.settings', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('user.settings-edit', compact('user'));
    }

    public function update(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
        'mobile' => 'required|string|max:15|unique:users,mobile,' . Auth::id(),
        'old_password' => 'nullable|current_password', // Validasi untuk old_password
        'new_password' => 'nullable|confirmed|min:8',
    ], [
        'name.required' => 'Nama harus diisi.',
        'email.required' => 'Email harus diisi.',
        'email.email' => 'Format email tidak valid.',
        'email.unique' => 'Email sudah terdaftar.',
        'mobile.required' => 'Nomor HP harus diisi.',
        'mobile.unique' => 'Nomor HP sudah terdaftar untuk pengguna lain.',
        'old_password.required' => 'Kata sandi saat ini harus diisi jika ingin mengganti kata sandi.',
        'old_password.current_password' => 'Kata sandi saat ini salah.',
        'new_password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        'new_password.min' => 'Kata sandi baru minimal harus 8 karakter.',
    ]);

    $user = Auth::user();

    if ($request->filled('new_password')) {
        // Tambahkan pemeriksaan untuk old_password sebelum memperbarui password
        if (!$request->filled('old_password')) {
            return redirect()->route('user.settings.edit')
                ->withErrors(['old_password.required' => 'Kata sandi saat ini harus diisi untuk mengganti kata sandi.'])
                ->withInput(); // Kembalikan input sebelumnya
        }

        if (!Hash::check($request->old_password, $user->password)) {
            return redirect()->route('user.settings.edit')
                ->withErrors(['old_password.current_password' => 'Kata sandi saat ini salah.'])
                ->withInput(); // Kembalikan input sebelumnya
        }
    }

    User::updateOrCreate(
        ['id' => $user->id],
        [
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => $request->filled('new_password') ? Hash::make($request->new_password) : $user->password,
        ]
    );

    return redirect()->route('user.settings.index')->with('status', 'Pengaturan telah diubah!');
}


    //admin settings
    public function admin_index()
    {
        $admin = Auth::user();
        return view('admin.settings', compact('admin'));
    }

    public function admin_edit()
    {
        $admin = Auth::user();
        return view('admin.settings-edit', compact('admin'));
    }

    public function admin_update(Request $request)
    {
        // Validasi input dari pengguna
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'mobile' => 'required|string|max:15|unique:users,mobile,' . Auth::id(),
            'old_password' => 'nullable|current_password',
            'new_password' => 'nullable|confirmed|min:8',
        ], [
            'name.required' => 'Nama harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'mobile.required' => 'Nomor HP harus diisi.',
            'mobile.unique' => 'Nomor HP sudah terdaftar untuk pengguna lain.',
            'old_password.required' => 'Password saat ini harus diisi.', // Pesan kesalahan untuk old_password
            'old_password.current_password' => 'Kata sandi saat ini salah.',
            'new_password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
            'new_password.min' => 'Kata sandi baru minimal harus 8 karakter.',
        ]);

        $admin = Auth::user();

        // Periksa apakah password baru diisi
        if ($request->filled('new_password')) {
            // Validasi jika password lama tidak diisi
            if (!$request->filled('old_password')) {
                return redirect()->route('admin.settings.edit')
                    ->withErrors(['old_password' => 'Kata sandi saat ini harus diisi.']) // Mengarahkan kembali dengan pesan kesalahan
                    ->withInput(); // Mengembalikan input sebelumnya
            }

            // Cek apakah password lama yang diisi benar
            if (!Hash::check($request->old_password, $admin->password)) {
                return redirect()->route('admin.settings.edit')
                    ->withErrors(['old_password' => 'Kata sandi saat ini salah.'])
                    ->withInput();
            }
        }

        // Update atau buat pengguna dengan data baru
        User::updateOrCreate(
            ['id' => $admin->id],
            [
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'password' => $request->filled('new_password') ? Hash::make($request->new_password) : $admin->password,
            ]
        );

        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan telah diubah!');
    }
}
