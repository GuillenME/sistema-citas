@extends('layouts.admin')

@section('title', 'Gestión de citas')

@section('content')

    {{-- MENSAJES --}}
    @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Servicio</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Empleado</th>
                    <th>Estado</th>
                    <th>Comprobante</th>
                    <th>Acciones</th>
                    <th>Observaciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($citas as $cita)
                    <tr>
                        <td>{{ $cita->client->user->name }}</td>
                        <td>{{ $cita->service->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($cita->date)->format('d/m/Y') }}</td>
                        <td>{{ $cita->start_time }} - {{ $cita->end_time }}</td>

                        <td>
                            {{ $cita->employee?->name ?? '— Sin asignar —' }}
                        </td>

                        <td>
                            <span class="estado {{ $cita->status }}">
                                {{ str_replace('_', ' ', ucfirst($cita->status)) }}
                            </span>
                        </td>

                        <td>
                            @if ($cita->receipt)
                                <a href="{{ asset('storage/' . $cita->receipt) }}" target="_blank">Ver</a>
                            @else
                                —
                            @endif
                        </td>

                        <td>
                            —
                        </td>

                        <td style="max-width:200px; text-align:left;">
                            {{ $cita->notes ?? '—' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
