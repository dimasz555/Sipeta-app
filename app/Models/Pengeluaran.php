<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;
    protected $table = 'pengeluaran_operasional_dan_kas';
    protected $fillable = [
        'jenis_pengeluaran_id',
        'deskripsi',
        'tgl_pengeluaran',
        'kode',
        'metode_pembayaran',
        'jumlah',
        'kategori',
    ];

    public function jenisPengeluaran()
    {
        return $this->belongsTo(JenisPengeluaran::class);
    }
}
