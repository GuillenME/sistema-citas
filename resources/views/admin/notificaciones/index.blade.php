@extends('layouts.admin')

@section('title', 'Notificaciones')
@section('back-url', route('admin.dashboard'))
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/notificaciones-index.css') }}">
@endsection

@section('content')
    <section class="admin-notif-page">
        <header class="admin-notif-topbar">

            @if (($unreadCount ?? 0) > 0)
                <form method="POST" action="{{ route('admin.notificaciones.read-all') }}" class="admin-notif-read-all-form">
                    @csrf
                    <button type="submit" class="admin-notif-read-all">Marcar todo como leido</button>
                </form>
            @endif
        </header>

        @if (session('status'))
            <div class="admin-notif-flash">
                {{ session('status') }}
            </div>
        @endif

        <div class="admin-notif-layout">
            <aside class="admin-notif-sidebar">
                <article class="admin-notif-card admin-notif-card-highlight">
                    <h3>Todas las notificaciones</h3>
                    <p>Revisa el historial completo y abre cada aviso para marcarlo como leido.</p>
                    <div class="admin-notif-card-metric">
                        <span>Notificaciones totales</span>
                        <strong>{{ $notificaciones->total() }}</strong>
                    </div>
                </article>

                <article class="admin-notif-card">
                    <div class="admin-notif-card-head">
                        <h3>Estado actual</h3>
                        <span class="admin-notif-pill">{{ $unreadCount ?? 0 }} pendientes</span>
                    </div>
                    <ul class="admin-notif-stats">
                        <li>
                            <span>No leidas</span>
                            <strong>{{ $unreadCount ?? 0 }}</strong>
                        </li>
                        <li>
                            <span>Leidas</span>
                            <strong>{{ $readCount ?? 0 }}</strong>
                        </li>
                        <li>
                            <span>En esta pagina</span>
                            <strong>{{ $notificaciones->count() }}</strong>
                        </li>
                    </ul>
                </article>
            </aside>

            <div class="admin-notif-shell">
                <div class="admin-notif-list">
                    @forelse ($notificaciones as $notificacion)
                        @php
                            $isUnread = is_null($notificacion->read_at);
                        @endphp
                        <a href="{{ route('admin.notificacion.leer', $notificacion->id) }}"
                            class="admin-notif-item {{ $isUnread ? 'is-unread' : '' }}">
                            <div class="admin-notif-item-accent" aria-hidden="true"></div>
                            <div class="admin-notif-item-main">
                                <span class="admin-notif-item-icon" aria-hidden="true"></span>
                                <div class="admin-notif-item-copy">
                                    <div class="admin-notif-item-head">
                                        <span class="admin-notif-item-status {{ $isUnread ? 'is-unread' : 'is-read' }}">
                                            {{ $isUnread ? 'Sin leer' : 'Leida' }}
                                        </span>
                                    </div>
                                    <strong>{{ $notificacion->data['mensaje'] ?? 'Notificacion' }}</strong>
                                </div>
                            </div>
                            <div class="admin-notif-item-meta">
                                <small>{{ $notificacion->created_at->diffForHumans() }}</small>
                            </div>
                            <span class="admin-notif-item-arrow" aria-hidden="true">&rsaquo;</span>
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
            </div>
        </div>
    </section>
@endsection

