@extends('layouts.admin')

@section('title', 'Gestion de citas')

@section('content')



    <div class="card table-card">
        <div class="table-container">

            <table class="admin-table admin-table-fixed">
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
                                {{ \Carbon\Carbon::parse($cita->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($cita->end_time)->format('H:i') }}
                            </td>

                            <td>
                                @if (!$cita->employee_id && $cita->status === 'confirmada')
                                    <form method="POST" action="{{ route('admin.citas.asignarEmpleado', $cita) }}">
                                        @csrf
                                        <select name="empleado_id">
                                            <option value="">Seleccionar empleado</option>
                                            @foreach ($empleados as $empleado)
                                                <option value="{{ $empleado->id }}">{{ $empleado->name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-save">Asignar</button>
                                    </form>
                                @else
                                    {{ $cita->employee?->name ?? '- Sin asignar -' }}
                                @endif
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
                                    -
                                @endif
                            </td>

                            <td class="table-actions">
                                <div class="actions-wrap">
                                    @if ($cita->receipt && $cita->status === 'pendiente_anticipo')
                                        <form method="POST" action="{{ route('admin.citas.confirmar', $cita) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-save">Confirmar</button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.citas.cancelar', $cita) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-cancel">Cancelar</button>
                                        </form>
                                    @else
                                        <span>-</span>
                                    @endif
                                </div>
                            </td>

                            <td class="notes-cell">
                                @if ($cita->notes)
                                    <button type="button"
                                        class="btn btn-edit notes-btn"
                                        data-notes="{{ e($cita->notes) }}">
                                        Ver
                                    </button>
                                @else
                                    -
                                @endif
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

    <div id="notesModal" class="notes-modal" aria-hidden="true">
        <div class="modal-box">
            <h3>Observaciones</h3>
            <p id="notesModalText">-</p>
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" id="notesModalClose">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    (function () {
        var modal = document.getElementById('notesModal');
        var modalText = document.getElementById('notesModalText');
        var closeBtn = document.getElementById('notesModalClose');

        if (!modal || !modalText || !closeBtn) return;

        document.querySelectorAll('.notes-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var text = btn.getAttribute('data-notes') || '-';
                modalText.textContent = text;
                modal.classList.add('active');
                modal.setAttribute('aria-hidden', 'false');
            });
        });

        function closeModal() {
            modal.classList.remove('active');
            modal.setAttribute('aria-hidden', 'true');
        }

        closeBtn.addEventListener('click', closeModal);
        modal.addEventListener('click', function (e) {
            if (e.target === modal) closeModal();
        });
    })();
</script>
@endsection



