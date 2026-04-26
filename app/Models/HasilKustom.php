<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilKustom extends Model
{
    protected $table = 'hasil_kustom';

    protected $fillable = [
        'dasbor_kustom_id',
        'fitur_id',
        'kolom',
        'baris',
        'status_fitur'
    ];

    public function fitur()
    {
        return $this->belongsTo(Fitur::class);
    }

    public function dashboard()
    {
        return $this->belongsTo(DasborKustom::class, 'dasbor_kustom_id');
    }
}