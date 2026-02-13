<div class="serv-shell">
    <div class="serv-panel">
        <header class="serv-head">
            <div>
                <h3>Listado de noticias y estado de publicacion.</h3>
            </div>
            <div class="serv-actions">
                <a href="{{ route('admin.noticias.create') }}" class="serv-btn primary">
                    <span class="serv-btn-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M8 12h8"/><path d="M12 8v8"/></svg>
                    </span>
                    Nueva noticia
                </a>
            </div>
        </header>

        <div class="table-container serv-table-wrap">
            <table class="admin-table serv-table">
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
                                <span class="serv-status {{ $noticia->published ? 'on' : 'off' }}">
                                    {{ $noticia->published ? 'Si' : 'No' }}
                                </span>
                            </td>
                            <td class="table-actions">

    <!-- 👁 PREVISUALIZAR -->
    <button
        class="btn-preview"
        wire:click="preview({{ $noticia->id }})"
        title="Previsualizar"
    >
        👁
    </button>

    <!-- ✏ EDITAR -->
    <a href="{{ route('admin.noticias.edit', $noticia) }}" class="btn-edit">
        Editar
    </a>

    <!-- 🗑 ELIMINAR -->
    <button class="btn-delete" wire:click="confirmDelete({{ $noticia->id }})">
        Eliminar
    </button>

</td>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="table-empty">No hay noticias registradas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrapper serv-pagination">
            <div class="pagination-info">
                Mostrando {{ $noticias->firstItem() ?? 0 }}-{{ $noticias->lastItem() ?? 0 }} de {{ $noticias->total() }} noticias
            </div>
            {{ $noticias->links() }}
        </div>
    </div>

    @if ($confirmDeleteId)
        <div class="modal-overlay" wire:click.self="cancelDelete">
            <div class="modal-box">
                <h3>Eliminar noticia?</h3>
                <p>Esta accion no se puede deshacer.</p>
                <div class="modal-actions">
                    <button class="btn btn-cancel" wire:click="cancelDelete">Cancelar</button>
                    <button class="btn btn-save" wire:click="deleteConfirmed" wire:loading.attr="disabled">
                        <span wire:loading.remove>Eliminar</span>
                        <span wire:loading>Eliminando...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
    @if ($showPreviewModal && $previewNoticia)
    <div class="modal-overlay" wire:click.self="closePreview">
        <div class="modal-box">

            <h2 style="margin-top:0;">
                {{ $previewNoticia->title }}
            </h2>

            {{-- Imagen guardada --}}
            @if ($previewNoticia->image)
                <img src="{{ asset('storage/' . $previewNoticia->image) }}"
                     style="width:100%; border-radius:12px; margin:15px 0;">
            @endif

            {{-- Contenido guardado --}}
            <p style="white-space: pre-line;">
                {{ $previewNoticia->content }}
            </p>

            {{-- Fecha guardada --}}
            <p style="margin-top:15px;">
                <strong>Fecha:</strong>
                {{ \Carbon\Carbon::parse($previewNoticia->publication_date)->format('d/m/Y') }}
            </p>

            {{-- Estado guardado --}}
            <p>
                <strong>Publicada:</strong>
                {{ $previewNoticia->published ? 'Sí' : 'No' }}
            </p>

            <div class="modal-actions" style="margin-top:20px;">
                <button class="btn btn-cancel" wire:click="closePreview">
                    Cerrar
                </button>
            </div>

        </div>
    </div>
@endif

</div>
