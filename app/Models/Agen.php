<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agen extends Model
{
    protected $table = 'agen';

    protected $fillable = [
        'user_id',
        'id_wazuh_agen',
        'nama_agen',
        'ip_agen',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}