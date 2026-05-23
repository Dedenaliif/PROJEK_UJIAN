<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $fillable = [
        'user_id',
        'nama_siswa',
        'nis',
        'kelas_id',
        'jurusan_id',
        'no_hp',
        'email',
        'nik',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function ujianSesi()
    {
        return $this->hasMany(UjianSiswaSesi::class);
    }
    public function percobaanUjian()
    {
        return $this->hasMany(PercobaanUjian::class);
    }
}
