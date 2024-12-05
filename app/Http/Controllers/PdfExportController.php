<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\HasilKerja;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;

class PdfExportController extends Controller
{
    public function exportHasilKerja($user_id, Request $request)
{
    if (Auth::user()->utype !== 'ADM') {
        return redirect()->route('home')->with('error', 'Anda tidak memiliki akses');
    }

    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    $karyawan = User::findOrFail($user_id);

    $query = HasilKerja::where('user_id', $user_id);

    if ($startDate && $endDate) {
        $query->whereBetween('tanggal_kerja', [$startDate, $endDate]);
    }

    $query->orderBy('tanggal_kerja', 'desc');

    $hasilKerja = $query->get();

    // Mengubah format tanggal menjadi ISO format (Y-m-d)
    $startDateISO = $startDate ? Carbon::parse($startDate)->format('Y-m-d') : null;
    $endDateISO = $endDate ? Carbon::parse($endDate)->format('Y-m-d') : null;

    // Format tanggal untuk periode
    $startDateFormatted = $startDateISO ? Carbon::parse($startDateISO)->translatedFormat('j F Y') : 'Tidak ditentukan';
    $endDateFormatted = $endDateISO ? Carbon::parse($endDateISO)->translatedFormat('j F Y') : 'Tidak ditentukan';

    $pdf = Pdf::loadView('pdf.hasil-kerja-kar', compact('karyawan', 'hasilKerja', 'startDateFormatted', 'endDateFormatted'))
        ->setPaper('a4', 'potrait');

    return $pdf->download('hasil_kerja.pdf');
}

    

}
