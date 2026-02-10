<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Cita;
use App\Models\Cliente;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword as ResetPasswordTrait;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable, ResetPasswordTrait;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'last_name',
        'phone',
        'email',
        'password',
        'role_id',
        'active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function appointments()
    {
        return $this->hasMany(Cita::class, 'client_id');
    }

    public function client()
    {
        return $this->hasOne(Cliente::class, 'user_id');
    }
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
