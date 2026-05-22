<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ujian extends Model
{
    protected $fillable = [
        'judul',
        'deskripsi',
        'waktu',
        'max_percobaan',
        'waktu_mulai',
        'waktu_selesai',
        'tipe'
    ];
    public function pertanyaans()
    {
        return $this->hasMany(Pertanyaan::class);
    }

    public function percobaanUjians()
    {
        return $this->hasMany(PercobaanUjian::class);
    }

    public function sesiSiswa()
    {
        return $this->hasMany(UjianSiswaSesi::class);
    }


}
