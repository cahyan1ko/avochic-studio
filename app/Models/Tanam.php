<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tanam extends Model
{
    use HasFactory;

    protected $table = 'tanam';

    protected $fillable = [
        'kebun_id',
        'jenis_tanaman',
        'jumlah_tanaman',
        'luas_tanam',
        'tanggal_tanam',
    ];

    protected $casts = [
        'tanggal_tanam' => 'date',
    ];

    public function kebun()
    {
        return $this->belongsTo(Kebun::class);
    }

    public function penyiraman()
    {
        return $this->hasMany(Penyiraman::class);
    }

    public function pemupukan()
    {
        return $this->hasMany(Pemupukan::class);
    }

    public function panen()
    {
        return $this->hasMany(Panen::class);
    }
}
