@extends('layouts.admin')

@section('title', 'Notificaciones')
@section('back-url', route('admin.dashboard'))

@section('content')
    <div class="card" style="padding: 0;">
        <div style="padding: 22px 24px; border-bottom: 1px solid rgba(252, 204, 124, 0.14);">
            <h3 style="margin: 0; color: #fff3df;">Todas las notificaciones</h3>
            <p style="margin: 8px 0 0; color: #d6c1a4;">Revisa el historial completo y abre cada aviso para marcarlo como leido.</p>
        </div>

        <div style="display: grid; gap: 0;">
            @forelse ($notificaciones as $notificacion)
                <a href="{{ route('admin.notificacion.leer', $notificacion->id) }}"
                    style="display: block; padding: 18px 24px; text-decoration: none; border-bottom: 1px solid rgba(252, 204, 124, 0.10); color: #fff3df; background: {{ $notificacion->read_at ? 'rgba(255,255,255,0.02)' : 'rgba(252, 204, 124, 0.06)' }};">
                    <div style="display: flex; justify-content: space-between; gap: 14px; align-items: start;">
                        <div style="display: grid; gap: 6px;">
                            <strong style="font-size: 15px; line-height: 1.45;">{{ $notificacion->data['mensaje'] ?? 'Notificacion' }}</strong>
                            <span style="font-size: 12px; color: #cdb391;">
                                {{ $notificacion->read_at ? 'Leida' : 'No leida' }}
                            </span>
                        </div>
                        <small style="color: #d6c1a4; white-space: nowrap;">{{ $notificacion->created_at->diffForHumans() }}</small>
                    </div>
                </a>
            @empty
                <div style="padding: 24px; color: #d6c1a4;">No hay notificaciones registradas.</div>
            @endforelse
        </div>

        @if ($notificaciones->hasPages())
            <div class="pagination-wrapper" style="padding: 20px 24px;">
                {{ $notificaciones->links('pagination::simple-bootstrap-4') }}
            </div>
        @endif
    </div>
@endsection
