<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PercobaanUjian extends Model
{
    protected $fillable = [
        'user_id',
        'ujian_id',
        'siswa_id',
        'jawaban_benar',
        'skor',
        'status',
        'waktu_mulai',
        'waktu_selesai',
        'skor_final',
        'dinilai_oleh',
        'percobaan_ke'
    ];


    public function user(){
        return $this->belongsTo(User::class);
    }
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
        return $this->hasMany(Jawaban::class);
    }
    protected $cast = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        // 'skor' => 'float',
    ];
}
