<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    #[Fillable([
        'name',
        'username',
        'email',
        'no_telp',
        'password',
        'role'
    ])]

    #[Hidden([
        'password',
        'remember_token'
    ])]


    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function dashboards()
    {
        return $this->hasMany(
            DasborKustom::class,
            'user_id'
        );
    }
}