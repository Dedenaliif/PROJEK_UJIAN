<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LatihanUjian extends Model
{
    protected $fillable = [
        'user_id',
        'ujian_id',
        'selesai',
        'nilai'
    ];
}
