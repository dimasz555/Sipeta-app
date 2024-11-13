<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembatalan extends Model
{
    use HasFactory;
    protected $table = 'pembatalan';
    protected $fillable = [
        'pembelian_id',
        'alasan_pembatalan',
        'tgl_pembatalan',
        'jumlah_pengembalian',
    ];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class);
    }

}
