<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Panen extends Model
{
    protected $table = 'panen';

    protected $fillable = [
        'tanam_id',
        'tanggal_panen',
        'hasil_panen',
        'satuan',
        'catatan',
    ];

    public function tanam()
    {
        return $this->belongsTo(Tanam::class);
    }
}
