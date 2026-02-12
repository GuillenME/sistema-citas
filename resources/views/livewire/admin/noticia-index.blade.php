<div>
    <div class="card table-card">
        <div class="table-container">

            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Titulo</th>
                        <th>Fecha</th>
                        <th>Publicada</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($noticias as $noticia)
                        <tr>
                            <td>{{ $noticia->title }}</td>
                            <td>{{ \Carbon\Carbon::parse($noticia->publication_date)->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge {{ $noticia->published ? 'badge-on' : 'badge-off' }}">
                                    {{ $noticia->published ? 'Si' : 'No' }}
                                </span>
                            </td>
                            <td class="table-actions">
                                <a href="{{ route('admin.noticias.edit', $noticia) }}" class="btn-edit">
                                    Editar
                                </a>
                                <button class="btn-delete" wire:click="confirmDelete({{ $noticia->id }})">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="table-empty">
                                No hay noticias registradas
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>

        <div class="pagination-wrapper">
            <div class="pagination-info">
                Pagina {{ $noticias->currentPage() }} de {{ $noticias->lastPage() }} ({{ $noticias->total() }} registros)
            </div>
            {{ $noticias->links() }}
        </div>
    </div>

    @if ($confirmDeleteId)
        <div class="modal-overlay" wire:click.self="cancelDelete">
            <div class="modal-box">
                <h3>¿Eliminar noticia?</h3>

                <p>Esta acción no se puede deshacer.</p>

                <div class="modal-actions">
                    <button class="btn btn-cancel" wire:click="cancelDelete">
                        Cancelar
                    </button>

                    <button class="btn btn-save" wire:click="deleteConfirmed" wire:loading.attr="disabled">
                        <span wire:loading.remove>Eliminar</span>
                        <span wire:loading>Eliminando...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
