<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blok extends Model
{
    use HasFactory;

    protected $table = 'bloks';
    protected $fillable = [
        'blok',
    ];

    public function bokings()
    {
        return $this->hasMany(Boking::class);
    }
}
