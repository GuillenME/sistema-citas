<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    // Note: Class name remains in Spanish for backward compatibility
    protected $table = 'clients';

    protected $fillable = [
        'user_id',
        'birth_date',
        'notes',
        'birth_date_change_count',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'birth_date_change_count' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }


    public function appointments()
    {
        return $this->hasMany(Cita::class, 'client_id');
    }
}
