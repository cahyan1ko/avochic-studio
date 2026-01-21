<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penyiraman extends Model
{
    protected $table = 'penyiraman';

    protected $fillable = [
        'tanam_id',
        'jam',
        'repeat',
        'jumlah_air',
    ];

    protected $casts = [
        'jumlah_air' => 'float',
    ];

    public function tanam()
    {
        return $this->belongsTo(Tanam::class);
    }
}
