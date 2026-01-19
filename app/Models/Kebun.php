<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kebun extends Model
{
    use HasFactory;

    protected $table = 'kebun';

    protected $fillable = [
        'user_id',
        'lokasi',
        'luas_kebun',
        'catatan',
    ];

    protected $appends = ['sisa_lahan'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tanam()
    {
        return $this->hasMany(Tanam::class);
    }

    public function getSisaLahanAttribute()
    {
        $totalTanam = $this->tanam()->sum('luas_tanam');
        return (float) $this->luas_kebun - (float) $totalTanam;
    }
}
