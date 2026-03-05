<?php

namespace App\Http\Requests;

use App\Models\Cita;
use App\Models\Servicio;
use App\Services\AppointmentAvailabilityService;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class AppointmentDateTimeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fecha' => ['required', 'date', 'after_or_equal:today'],
            'hora_inicio' => ['required', 'date_format:H:i'],
            'observaciones' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'fecha.required' => 'Debes seleccionar una fecha.',
            'fecha.date' => 'La fecha no es valida.',
            'fecha.after_or_equal' => 'La fecha debe ser hoy o posterior.',
            'hora_inicio.required' => 'Debes seleccionar una hora.',
            'hora_inicio.date_format' => 'La hora debe tener formato HH:MM.',
            'observaciones.max' => 'Las observaciones no pueden exceder 500 caracteres.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $fecha = Carbon::parse((string) $this->input('fecha'));
            if ($fecha->isSunday()) {
                $validator->errors()->add('fecha', 'Los domingos no se atiende. Por favor selecciona otro dia.');
                return;
            }

            $duration = $this->resolveDurationMinutes();
            if ($duration <= 0) {
                return;
            }

            $horaInicio = Carbon::parse((string) $this->input('hora_inicio'));
            $horaFin = $horaInicio->copy()->addMinutes($duration);

            $availability = app(AppointmentAvailabilityService::class);
            if ($availability->isInsideLunchBreak($horaInicio->format('H:i'), $horaFin->format('H:i'))) {
                $validator->errors()->add('hora_inicio', 'Ese horario corresponde a la hora de comida. Elige otro bloque.');
            }
        });
    }

    private function resolveDurationMinutes(): int
    {
        if ($this->filled('servicio_id')) {
            $servicio = Servicio::query()
                ->select(['id', 'duration_minutes'])
                ->find($this->input('servicio_id'));

            return (int) ($servicio->duration_minutes ?? 0);
        }

        $cita = $this->route('cita');
        if ($cita instanceof Cita) {
            $cita->loadMissing('service:id,duration_minutes');
            return (int) ($cita->service->duration_minutes ?? 0);
        }

        return 0;
    }
}

