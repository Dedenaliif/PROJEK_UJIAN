<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PercobaanUjian extends Model
{
    protected $fillable = [
        'user_id',
        'ujian_id',
        'siswa_id',
        'skor_total',
        'status'
    ];

    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function jawabans()
    {
        return $this->hasMany(Jawaban::class, 'percobaan_ujian_id');
    }
}
