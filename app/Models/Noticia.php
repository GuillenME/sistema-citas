<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// app/Models/Noticia.php
class Noticia extends Model
{
    protected $table = 'noticias';

    protected $fillable = [
        'titulo',
        'slug',
        'contenido',
        'imagen',
        'fecha_publicacion',
        'publicada',
        'usuario_id'
    ];
}
