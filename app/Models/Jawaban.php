<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jawaban extends Model
{
    protected $fillable = [
        'pertanyaan_id',
        'percobaan_ujian_id',
        'benar',
        'skor',
        'pilihan_jawaban',
    ];

    
}
