<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemupukan extends Model
{
    use HasFactory;

    protected $table = 'pemupukan';

    protected $fillable = [
        'tanam_id',
        'jenis_pupuk',
        'jam',
        'repeat',
        'jumlah_pupuk',
    ];

    public function tanam()
    {
        return $this->belongsTo(Tanam::class);
    }
}
