<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sesi extends Model
{
    protected $fillable = [
        'no_sesi',
        'jam_mulai',
        'jam_selesai'
    ];

    public function getJamAttribute()
    {
        return substr($this->jam_mulai,0,5)
            .' - '.
            substr($this->jam_selesai,0,5);
    }
}
