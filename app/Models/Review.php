<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Service;
use App\Models\Usuario;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_email',
        'service_id',
        'comment',
        'rating',
    ];

    public function service()
{
    return $this->belongsTo(Service::class);
}

public function user()
{
    return $this->belongsTo(\App\Models\Usuario::class, 'user_id');
}

}
