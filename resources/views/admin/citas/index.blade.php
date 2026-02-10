@extends('layouts.admin')

@section('title', 'Gestion de citas')

@section('content')



    <div class="card table-card">
        <div class="table-toolbar">
            <input type="text" id="citasSearch" class="table-search" placeholder="Buscar cliente o servicio">
        </div>
        <div class="table-container">

            <table class="admin-table admin-table-fixed">
                <colgroup>
                    <col class="col-cliente">
                    <col class="col-servicio">
                    <col class="col-fecha">
                    <col class="col-estado">
                    <col class="col-acciones">
                </colgroup>
                <thead>
                    <tr>
                        <th class="col-cliente">Cliente</th>
                        <th class="col-servicio">Servicio</th>
                        <th class="col-fecha">Fecha/Hora</th>
                        <th class="col-estado">Estado</th>
                        <th class="col-acciones">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($citas as $cita)
                        @php
                            $isToday = \Carbon\Carbon::parse($cita->date)->isToday();
                            $isTomorrow = \Carbon\Carbon::parse($cita->date)->isTomorrow();
                            $rowClass = $isToday ? 'row-today' : ($isTomorrow ? 'row-soon' : '');
                            $searchText = strtolower(trim(
                                ($cita->client->user->name ?? '') . ' ' .
                                ($cita->service->name ?? '') . ' ' .
                                ($cita->status ?? '')
                            ));
                        @endphp
                        <tr class="{{ $rowClass }}" data-search="{{ $searchText }}">
                            <td class="col-cliente">{{ $cita->client->user->name }}</td>

                            <td class="col-servicio">{{ $cita->service->name }}</td>

                            <td class="col-fecha">
                                {{ \Carbon\Carbon::parse($cita->date)->format('d/m/Y') }}
                                {{ \Carbon\Carbon::parse($cita->start_time)->format('h:i A') }}-{{ \Carbon\Carbon::parse($cita->end_time)->format('h:i A') }}
                                @if ($isToday)
                                    <span class="badge badge-today">Hoy</span>
                                @elseif ($isTomorrow)
                                    <span class="badge badge-soon">Ma&ntilde;ana</span>
                                @endif
                            </td>

                            <td class="col-estado">
                                @php
                                    $statusClass = match ($cita->status) {
                                        'confirmada' => 'badge-on',
                                        'cancelada' => 'badge-off',
                                        'pendiente_anticipo' => 'badge-pending',
                                        default => 'badge-off',
                                    };
                                @endphp

                                <span class="badge {{ $statusClass }}">
                                    {{ ucfirst($cita->status) }}
                                </span>
                            </td>

                            <td class="table-actions col-acciones">
                                <div class="actions-wrap">
                                    <button type="button"
                                        class="btn btn-edit btn-compact notes-btn"
                                        data-notes="{{ e($cita->notes ?? '') }}"
                                        data-employee="{{ e($cita->employee?->name ?? '- Sin asignar -') }}"
                                        data-receipt="{{ $cita->receipt ? asset('storage/' . $cita->receipt) : '' }}"
                                        data-assign-action="{{ route('admin.citas.asignarEmpleado', $cita) }}"
                                        data-can-assign="{{ $cita->status === 'confirmada' && !$cita->employee_id ? '1' : '0' }}"
                                        data-can-confirm="{{ $cita->receipt && $cita->status === 'pendiente_anticipo' ? '1' : '0' }}"
                                        data-confirm-action="{{ route('admin.citas.confirmar', $cita) }}"
                                        data-cancel-action="{{ route('admin.citas.cancelar', $cita) }}">
                                        Detalles
                                    </button>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="table-empty">
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
            <h3>Detalle de la cita</h3>
            <p><strong>Empleado:</strong> <span id="notesModalEmployee">-</span></p>
            <p><strong>Comprobante:</strong> <span id="notesModalReceipt">-</span></p>
            <p><strong>Comentario:</strong> <span id="notesModalText">-</span></p>
            <form method="POST" id="assignForm" class="assign-inline">
                @csrf
                <select name="empleado_id" id="assignEmployeeSelect" class="select-compact">
                    <option value="">Seleccionar empleado</option>
                    @foreach ($empleados as $empleado)
                        <option value="{{ $empleado->id }}">{{ $empleado->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-save btn-compact">Asignar</button>
            </form>
            <form method="POST" id="confirmForm" class="assign-inline">
                @csrf
                <button type="submit" class="btn btn-save btn-compact">Confirmar</button>
                <button type="button" class="btn btn-cancel btn-compact" id="openCancelFromModal">Cancelar</button>
            </form>
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" id="notesModalClose">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <div id="cancelModal" class="notes-modal" aria-hidden="true">
        <div class="modal-box">
            <h3>Cancelar cita</h3>
            <form method="POST" id="cancelForm">
                @csrf
                <label for="cancelNotes">Observaciones (opcional)</label>
                <textarea id="cancelNotes" name="observaciones" rows="4" placeholder="Motivo de cancelacion..."></textarea>
                <div class="modal-actions">
                    <button type="button" class="btn btn-cancel" id="cancelModalClose">Cerrar</button>
                    <button type="submit" class="btn btn-save">Confirmar cancelacion</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    (function () {
        var searchInput = document.getElementById('citasSearch');
        var rows = Array.prototype.slice.call(document.querySelectorAll('tbody tr[data-search]'));
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                var q = searchInput.value.toLowerCase().trim();
                rows.forEach(function (row) {
                    var haystack = row.getAttribute('data-search') || '';
                    row.style.display = haystack.includes(q) ? '' : 'none';
                });
            });
        }

        var modal = document.getElementById('notesModal');
        var modalText = document.getElementById('notesModalText');
        var modalEmployee = document.getElementById('notesModalEmployee');
        var modalReceipt = document.getElementById('notesModalReceipt');
        var assignForm = document.getElementById('assignForm');
        var assignSelect = document.getElementById('assignEmployeeSelect');
        var confirmForm = document.getElementById('confirmForm');
        var openCancelFromModal = document.getElementById('openCancelFromModal');
        var closeBtn = document.getElementById('notesModalClose');

        if (!modal || !modalText || !closeBtn) return;

        document.querySelectorAll('.notes-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var text = btn.getAttribute('data-notes') || '-';
                var employee = btn.getAttribute('data-employee') || '-';
                var receipt = btn.getAttribute('data-receipt') || '';
                var assignAction = btn.getAttribute('data-assign-action') || '';
                var canAssign = btn.getAttribute('data-can-assign') === '1';
                var canConfirm = btn.getAttribute('data-can-confirm') === '1';
                var confirmAction = btn.getAttribute('data-confirm-action') || '';
                var cancelAction = btn.getAttribute('data-cancel-action') || '';

                modalText.textContent = text;
                modalEmployee.textContent = employee;

                if (receipt) {
                    modalReceipt.innerHTML = '<a href="' + receipt + '" target="_blank" rel="noopener">Ver comprobante</a>';
                } else {
                    modalReceipt.textContent = '-';
                }

                if (assignForm && assignSelect) {
                    assignForm.style.display = canAssign ? 'inline-flex' : 'none';
                    assignForm.setAttribute('action', assignAction);
                    assignSelect.value = '';
                }
                if (confirmForm) {
                    confirmForm.style.display = canConfirm ? 'inline-flex' : 'none';
                    confirmForm.setAttribute('action', confirmAction);
                }
                if (openCancelFromModal) {
                    openCancelFromModal.setAttribute('data-cancel-action', cancelAction);
                }
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

        var cancelModal = document.getElementById('cancelModal');
        var cancelForm = document.getElementById('cancelForm');
        var cancelClose = document.getElementById('cancelModalClose');
        if (cancelModal && cancelForm && cancelClose) {
            function openCancel(action) {
                cancelForm.setAttribute('action', action);
                cancelModal.classList.add('active');
                cancelModal.setAttribute('aria-hidden', 'false');
            }

            if (openCancelFromModal) {
                openCancelFromModal.addEventListener('click', function () {
                    var action = openCancelFromModal.getAttribute('data-cancel-action');
                    if (action) {
                        closeModal();
                        openCancel(action);
                    }
                });
            }

            function closeCancel() {
                cancelModal.classList.remove('active');
                cancelModal.setAttribute('aria-hidden', 'true');
            }

            cancelClose.addEventListener('click', closeCancel);
            cancelModal.addEventListener('click', function (e) {
                if (e.target === cancelModal) closeCancel();
            });
        }
    })();
</script>
@endsection
