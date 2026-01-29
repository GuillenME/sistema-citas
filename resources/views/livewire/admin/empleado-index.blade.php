<div>

    <div class="header">
        <div class="header-left">
            <a href="{{ route('admin.dashboard') }}" class="back-arrow">←</a>
            <h1>Empleados</h1>
        </div>

        <a href="{{ route('admin.empleados.create') }}" class="btn btn-save">
            + Nuevo empleado
        </a>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Especialidad</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($empleados as $e)
                    <tr>
                        <td>{{ $e->name }}</td>
                        <td>{{ $e->specialty }}</td>
                        <td>
                            @if($e->active)
                                <span class="badge badge-on">Activo</span>
                            @else
                                <span class="badge badge-off">Inactivo</span>
                            @endif
                        </td>
                        <td class="actions">
                            <a href="{{ route('admin.empleados.edit', $e) }}" class="btn-edit">
                                Editar
                            </a>

                            <button class="btn-delete" wire:click="toggle({{ $e->id }})">
                                {{ $e->active ? 'Desactivar' : 'Activar' }}
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
