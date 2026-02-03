@extends('layouts.admin')

@section('title', 'Gestión de citas')

@section('content')



    <div class="card table-card">
        <div class="table-container">

            <table class="admin-table">
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
                    @forelse ($citas as $cita)
                        <tr>
                            <td>{{ $cita->client->user->name }}</td>

                            <td>{{ $cita->service->name }}</td>

                            <td>{{ \Carbon\Carbon::parse($cita->date)->format('d/m/Y') }}</td>

                            <td>
                                {{ $cita->start_time }} – {{ $cita->end_time }}
                            </td>

                            <td>
                                {{ $cita->employee?->name ?? '— Sin asignar —' }}
                            </td>

                            <td>
                                @php
                                    $statusClass = match ($cita->status) {
                                        'confirmada' => 'badge-on',
                                        'cancelada' => 'badge-off',
                                        default => 'badge-off',
                                    };
                                @endphp

                                <span class="badge {{ $statusClass }}">
                                    {{ ucfirst($cita->status) }}
                                </span>
                            </td>

                            <td>
                                @if ($cita->receipt)
                                    <a href="{{ asset('storage/' . $cita->receipt) }}" class="btn-edit">
                                        Ver
                                    </a>
                                @else
                                    —
                                @endif
                            </td>

                            <td class="table-actions">
                                —
                            </td>

                            <td class="notes-cell">
                                {{ $cita->notes ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="table-empty">
                                No hay citas registradas
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>

        <div class="pagination-wrapper">
            {{ $citas->links('pagination::simple-bootstrap-4') }}
        </div>
    </div>

@endsection
