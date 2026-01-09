<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';
    use HasFactory;
    protected $fillable = ['nombre', 'email', 'password', 'rol_id'];

}
