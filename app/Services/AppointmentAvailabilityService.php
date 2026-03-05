<?php

namespace App\Services;

use App\Constants\CitaStatus;
use App\Models\Cita;
use App\Models\Empleado;
use App\Models\Servicio;
use Carbon\Carbon;

class AppointmentAvailabilityService
{
    public function isInsideLunchBreak(string $horaInicio, string $horaFin): bool
    {
        $comidaInicio = (string) config('citas.horarios.comida_inicio', '15:00');
        $comidaFin = (string) config('citas.horarios.comida_fin', '16:00');

        if (!preg_match('/^\d{2}:\d{2}$/', $comidaInicio) || !preg_match('/^\d{2}:\d{2}$/', $comidaFin)) {
            return false;
        }

        if ($comidaInicio >= $comidaFin) {
            return false;
        }

        return $horaInicio < $comidaFin && $horaFin > $comidaInicio;
    }

    public function employeeCoversRange(Empleado $empleado, Carbon $fecha, string $horaInicio, string $horaFin): bool
    {
        $diaSemana = $fecha->dayOfWeek;

        return $empleado->schedules
            ->where('day_of_week', $diaSemana)
            ->contains(function ($horario) use ($horaInicio, $horaFin) {
                $inicio = Carbon::parse($horario->start_time)->format('H:i');
                $fin = Carbon::parse($horario->end_time)->format('H:i');

                return $horaInicio >= $inicio
                    && $horaFin <= $fin
                    && !$this->isInsideLunchBreak($horaInicio, $horaFin);
            });
    }

    public function hasOverlap(array $bloques, string $horaInicio, string $horaFin): bool
    {
        foreach ($bloques as $bloque) {
            if ($horaInicio < $bloque['fin'] && $horaFin > $bloque['inicio']) {
                return true;
            }
        }

        return false;
    }

    public function buildAvailableBlocksForService(Servicio $servicio, Carbon $fecha): array
    {
        $empleados = $servicio->empleados;
        if ($empleados->isEmpty()) {
            return [];
        }

        $diaSemana = $fecha->dayOfWeek;
        $duracion = (int) $servicio->duration_minutes;
        $intervalo = (int) config('citas.horarios.intervalo_minutos', 15);
        $minimoHoy = (int) config('citas.horarios.hora_minima_adelantada', 60);

        $citasDelDia = Cita::whereDate('date', $fecha->toDateString())
            ->whereIn('employee_id', $empleados->pluck('id'))
            ->whereIn('status', [
                CitaStatus::CONFIRMADA,
                CitaStatus::PENDIENTE_ANTICIPO,
            ])
            ->get()
            ->groupBy('employee_id');

        $bloquesDisponibles = [];

        foreach ($empleados as $empleado) {
            $horarios = $empleado->schedules->where('day_of_week', $diaSemana);
            if ($horarios->isEmpty()) {
                continue;
            }

            foreach ($horarios as $horario) {
                $inicioR = Carbon::parse($horario->start_time)->hour * 60
                    + Carbon::parse($horario->start_time)->minute;
                $finR = Carbon::parse($horario->end_time)->hour * 60
                    + Carbon::parse($horario->end_time)->minute;

                if ($fecha->isToday()) {
                    $horaActual = Carbon::now()->addMinutes($minimoHoy);
                    $horaMinima = $horaActual->hour * 60 + $horaActual->minute;
                    $inicioR = max($inicioR, $horaMinima);
                }

                for ($min = $inicioR; $min + $duracion <= $finR; $min += $intervalo) {
                    $finBloque = $min + $duracion;
                    $citasEmpleado = $citasDelDia->get($empleado->id, collect());
                    $ocupado = false;
                    $inicioBloque = sprintf('%02d:%02d', intdiv($min, 60), $min % 60);
                    $finBloqueFmt = sprintf('%02d:%02d', intdiv($finBloque, 60), $finBloque % 60);

                    if ($this->isInsideLunchBreak($inicioBloque, $finBloqueFmt)) {
                        continue;
                    }

                    foreach ($citasEmpleado as $cita) {
                        $inicioCita = Carbon::parse($cita->start_time)->hour * 60
                            + Carbon::parse($cita->start_time)->minute;
                        $finCita = Carbon::parse($cita->end_time)->hour * 60
                            + Carbon::parse($cita->end_time)->minute;

                        if ($min < $finCita && $finBloque > $inicioCita) {
                            $ocupado = true;
                            break;
                        }
                    }

                    if (!$ocupado) {
                        $bloquesDisponibles[] = [
                            'inicio' => $inicioBloque,
                            'fin' => $finBloqueFmt,
                        ];
                    }
                }
            }
        }

        return collect($bloquesDisponibles)
            ->unique('inicio')
            ->sortBy('inicio')
            ->values()
            ->all();
    }

    public function findAssignableEmployee(
        Servicio $servicio,
        Carbon $fecha,
        string $horaInicio,
        string $horaFin,
        bool $useLock = false,
        ?int $excludeAppointmentId = null
    ): ?Empleado {
        foreach ($servicio->empleados as $empleado) {
            if (!$this->employeeCoversRange($empleado, $fecha, $horaInicio, $horaFin)) {
                continue;
            }

            $query = Cita::where('employee_id', $empleado->id)
                ->whereDate('date', $fecha->toDateString())
                ->whereIn('status', [CitaStatus::CONFIRMADA, CitaStatus::PENDIENTE_ANTICIPO])
                ->where(function ($q) use ($horaInicio, $horaFin) {
                    $q->where('start_time', '<', $horaFin)
                        ->where('end_time', '>', $horaInicio);
                });

            if ($excludeAppointmentId !== null) {
                $query->where('id', '!=', $excludeAppointmentId);
            }

            if ($useLock) {
                $query->lockForUpdate();
            }

            if (!$query->exists()) {
                return $empleado;
            }
        }

        return null;
    }
}
