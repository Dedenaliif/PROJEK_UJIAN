<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pertanyaan extends Model
{
    protected $fillable = [
        'ujian_id',
        'text_pertanyaan',
        'opsi_a',
        'opsi_b',
        'opsi_c',
        'opsi_d',
        'jawaban_benar',
        'tipe',
        'skor'
    ];

    public function ujians()
    {
        return $this->belongsTo(Ujian::class);
    }
}
