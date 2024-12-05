<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisGenteng extends Model
{
    use HasFactory;

    // Menentukan nama tabel jika tidak mengikuti konvensi plural
    protected $table = 'jenis_genteng';

    // Kolom yang dapat diisi
    protected $fillable = [
        'nama_jenis',
        'gaji_per_seribu',
    ];

    // Relasi: Setiap jenis genteng dapat dimiliki oleh banyak karyawan
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
