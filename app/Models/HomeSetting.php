<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeSetting extends Model
{
    use HasFactory;

    protected $table = 'home_settings';

    protected $fillable = [
        'hero_title',
        'hero_subtitle',
        'register_subtitle',
        'feature_1_title',
        'feature_1_description',
        'feature_2_title',
        'feature_2_description',
        'feature_3_title',
        'feature_3_description',
        'hero_image',
        'navbar_logo',
        'footer_address',
        'footer_references',
        'footer_phone',
        'footer_whatsapp',
        'footer_hours',
        'terms_content',
        'privacy_policy_content',
        'terms_updated_at',
        'privacy_policy_updated_at',
    ];

    protected $casts = [
        'terms_updated_at' => 'datetime',
        'privacy_policy_updated_at' => 'datetime',
    ];

    public static function defaultAttributes(): array
    {
        return [
            'hero_title' => 'BARBERIA & SPA',
            'hero_subtitle' => 'Estilo, cuidado y bienestar en un solo lugar',
            'register_subtitle' => 'Unete a nuestra comunidad exclusiva y reserva tu proxima experiencia de lujo.',
            'feature_1_title' => 'Cortes Modernos',
            'feature_1_description' => 'Tecnicas actuales y tendencias',
            'feature_2_title' => 'Tratamientos Spa',
            'feature_2_description' => 'Relajacion y cuidado personal',
            'feature_3_title' => 'Atencion Personalizada',
            'feature_3_description' => 'Productos de primera linea',
            'footer_address' => 'Calle Principal #123 - Guadalajara',
            'footer_references' => null,
            'footer_phone' => '3312345678',
            'footer_whatsapp' => '3312345678',
            'footer_hours' => 'Lun-Sab 9:00-20:00',
            'terms_content' => implode("\n\n", [
                'Uso del servicio',
                '- El usuario debe proporcionar informacion veridica.',
                '- El sistema debe utilizarse unicamente para agendar citas.',
                '',
                'Reservaciones y pagos',
                '- Se requiere anticipo para confirmar la cita.',
                '- El comprobante debe ser valido.',
                '',
                'Cancelaciones',
                '- Las cancelaciones tardias pueden perder el anticipo.',
                '',
                'Responsabilidad',
                '- No nos hacemos responsables por datos incorrectos proporcionados por el usuario.',
            ]),
            'privacy_policy_content' => implode("\n\n", [
                'Informacion recopilada',
                'Recopilamos los datos necesarios para gestionar citas dentro del sistema.',
                '',
                'Uso de la informacion',
                'La informacion se utiliza exclusivamente para la gestion de citas, la comunicacion con el cliente y la mejora del servicio.',
                '',
                'Proteccion de datos',
                'Aplicamos medidas de seguridad para proteger la informacion personal contra accesos no autorizados.',
                '',
                'Derechos del usuario',
                'El usuario puede solicitar la modificacion o eliminacion de sus datos personales en cualquier momento.',
            ]),
            'terms_updated_at' => now(),
            'privacy_policy_updated_at' => now(),
        ];
    }
}
