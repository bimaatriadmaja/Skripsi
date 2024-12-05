<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilKerja extends Model
{
    use HasFactory;

    protected $table = 'hasil_kerja';

    protected $fillable = [
        'user_id',
        'tanggal_kerja',
        'jumlah_genteng',
        'catatan',
        'status', // persetujuan
        'payment_status', // pembayaran
    ];

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
