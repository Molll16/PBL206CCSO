<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DasborKustom extends Model
{
    protected $table = 'dasbor_kustom';

    protected $fillable = [
        'user_id',
        'nama_dasbor',
        'jenis_dasbor',
        'status_dasbor'
    ];


    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }


    public function hasilKustom()
    {
        return $this->hasMany(
            HasilKustom::class,
            'dasbor_kustom_id'
        );
    }
}