<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fitur extends Model
{
    protected $table = 'fitur';

    protected $fillable = [
        'nama_fitur'
    ];


    public function hasilKustom()
    {
        return $this->hasMany(
            HasilKustom::class,
            'fitur_id'
        );
    }
}