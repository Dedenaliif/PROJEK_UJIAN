<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PercobaanUjian extends Model
{
    protected $fillable = [
        'user_id',
        'ujian_id',
        'siswa_id',
        'skor',
        'status',
        'waktu_mulai',
        'waktu_selesai',
        'percobaan_ke'
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
    protected $cast = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        // 'skor' => 'float',
    ];
}
