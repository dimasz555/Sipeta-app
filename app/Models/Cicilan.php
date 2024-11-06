<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cicilan extends Model
{
    use HasFactory;

    protected $table = 'cicilan';
    protected $fillable = [
        'no_transaksi',
        'pembelian_id',
        'no_cicilan',
        'harga_cicilan',
        'tgl_bayar',
        'bulan',
        'tahun',
        'kwitansi',
        'status',
    ];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class);
    }
}
