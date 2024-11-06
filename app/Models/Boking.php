<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boking extends Model
{
    use HasFactory;

    protected $table = 'bokings';
    protected $fillable = [
        'user_id',
        'project_id',
        'blok_id',
        'no_blok',
        'tgl_boking',
        'harga_boking',
        'tgl_lunas',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function blok()
    {
        return $this->belongsTo(Blok::class);
    }
}
