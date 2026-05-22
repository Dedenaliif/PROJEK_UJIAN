<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UjianSiswaSesi extends Model
{
    protected $table = 'ujian_siswa_sesi';

    protected $fillable = [
        'ujian_id',
        'sesi_id',
        'siswa_id'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function sesi()
    {
        return $this->belongsTo(Sesi::class);
    }

    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }
}
