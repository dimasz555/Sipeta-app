<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;


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

     // Menambahkan accessor untuk encrypted_id
     public function getEncryptedIdAttribute()
     {
         return Crypt::encrypt($this->id);
     }

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class);
    }

}
