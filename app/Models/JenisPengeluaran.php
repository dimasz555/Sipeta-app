<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPengeluaran extends Model
{
    use HasFactory;
    protected $table = 'jenis_pengeluaran';
    protected $fillable = [
        'name',
    ];


    public function pengeluarans()
    {
        return $this->hasMany(Pengeluaran::class);
    }
}
