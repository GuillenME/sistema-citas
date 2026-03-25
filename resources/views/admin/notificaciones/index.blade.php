@extends('layouts.admin')

@section('title', 'Notificaciones')
@section('back-url', route('admin.dashboard'))
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/notificaciones-index.css') }}">
@endsection

@section('content')
    <section class="admin-notif-shell">
        <header class="admin-notif-hero">
            <div>
                <span class="admin-notif-kicker">Centro de actividad</span>
                <h2>Todas las notificaciones</h2>
                <p>Revisa el historial completo y abre cada aviso para marcarlo como leido.</p>
            </div>
            <div class="admin-notif-hero-stat">
                <span>Pendientes</span>
                <strong>{{ $notificaciones->whereNull('read_at')->count() }}</strong>
            </div>
        </header>

        <div class="admin-notif-list">
            @forelse ($notificaciones as $notificacion)
                @php
                    $isUnread = is_null($notificacion->read_at);
                @endphp
                <a href="{{ route('admin.notificacion.leer', $notificacion->id) }}"
                    class="admin-notif-item {{ $isUnread ? 'is-unread' : '' }}">
                    <div class="admin-notif-item-main">
                        <span class="admin-notif-item-icon" aria-hidden="true"></span>
                        <div class="admin-notif-item-copy">
                            <strong>{{ $notificacion->data['mensaje'] ?? 'Notificacion' }}</strong>
                            <span class="admin-notif-item-status {{ $isUnread ? 'is-unread' : 'is-read' }}">
                                {{ $isUnread ? 'No leida' : 'Leida' }}
                            </span>
                        </div>
                    </div>
                    <div class="admin-notif-item-meta">
                        <small>{{ $notificacion->created_at->diffForHumans() }}</small>
                        <span class="admin-notif-item-arrow" aria-hidden="true">&rarr;</span>
                    </div>
                </a>
            @empty
                <div class="admin-notif-empty">
                    <div class="admin-notif-empty-icon" aria-hidden="true"></div>
                    <strong>No hay notificaciones registradas</strong>
                    <p>Cuando ocurra algo importante en el sistema, aparecera aqui.</p>
                </div>
            @endforelse
        </div>

        @if ($notificaciones->hasPages())
            <div class="pagination-wrapper admin-notif-pagination">
                <div class="admin-notif-pagination-copy">
                    Mostrando {{ $notificaciones->firstItem() }}-{{ $notificaciones->lastItem() }}
                    de {{ $notificaciones->total() }} notificaciones
                </div>
                {{ $notificaciones->links('pagination::simple-bootstrap-4') }}
            </div>
        @endif
    </section>
@endsection
