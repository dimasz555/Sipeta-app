<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelian';
    protected $fillable = [
        'user_id',
        'boking_id',
        'tgl_pembelian',
        'harga',
        'dp',
        'jumlah_bulan_cicilan',
        'harga_cicilan_perbulan',
        'pjb',
        'tgl_lunas',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function boking()
    {
        return $this->belongsTo(Boking::class);
    }

    public function cicilans()
    {
        return $this->hasMany(Cicilan::class);
    }
}
