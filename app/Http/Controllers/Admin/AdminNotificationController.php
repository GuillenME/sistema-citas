<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminNotificationController extends Controller
{
    public function read(string $id)
    {
        /** @var \App\Models\Usuario $user */
        $user = auth()->user();
        $notification = $user->notifications()->findOrFail($id);
        $notification->markAsRead();

        $appointmentId = $notification->data['cita_id'] ?? null;

        if ($appointmentId) {
            return redirect()->route('admin.citas.index', ['focus_cita' => $appointmentId]);
        }

        return redirect()->route('admin.citas.index');
    }

    public function readAll()
    {
        /** @var \App\Models\Usuario $user */
        $user = auth()->user();
        $user->unreadNotifications->markAsRead();

        return redirect()
            ->route('admin.notificaciones.index')
            ->with('status', 'Todas las notificaciones fueron marcadas como leidas.');
    }

    public function json()
    {
        /** @var \App\Models\Usuario $user */
        $user = auth()->user();

        return response()->json([
            'count' => $user->unreadNotifications->count(),
            'notificaciones' => $user->unreadNotifications->take(5)->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'mensaje' => $notification->data['mensaje'],
                    'tiempo' => $notification->created_at->diffForHumans(),
                ];
            }),
        ]);
    }

    public function index()
    {
        /** @var \App\Models\Usuario $user */
        $user = auth()->user();
        $notificaciones = $user->notifications()
            ->latest()
            ->paginate(5)
            ->onEachSide(1);
        $unreadCount = $user->unreadNotifications()->count();
        $readCount = max(0, $notificaciones->total() - $unreadCount);

        return view('admin.notificaciones.index', compact('notificaciones', 'unreadCount', 'readCount'));
    }
}
