<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Service;
use App\Models\Usuario;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $usuario = Usuario::find(auth()->id());

        $reviews = Review::where('user_id', $usuario->id)
            ->with('service')
            ->orderBy('created_at', 'desc')
            ->paginate(3);

        // 🔹 Solo servicios que el usuario reservó
        $services = $usuario
            ->appointments()
            ->with('service')
            ->get()
            ->pluck('service')
            ->unique('id')
            ->values();

        return view('cliente.comentarios', compact('reviews', 'services'));
    }

    public function store(Request $request)
    {
        $usuario = Usuario::find(auth()->id());

        $data = $request->validate([
            'service_id' => 'required|exists:services,id',
            'comment' => 'required|string|max:1000',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        // 🔒 Validar que el servicio realmente fue reservado por el usuario
        $validService = $usuario
            ->appointments()
            ->where('service_id', $data['service_id'])
            ->exists();

        if (!$validService) {
            return back()->withErrors([
                'service_id' => 'No puedes comentar un servicio que no has reservado.'
            ]);
        }

        Review::create([
            'user_id' => $usuario->id,
            'user_email' => $usuario->email,
            'service_id' => $data['service_id'],
            'comment' => $data['comment'],
            'rating' => $data['rating'] ?? null,
        ]);

        return redirect()->route('cliente.comentarios')
            ->with('success', 'Gracias por tu comentario.');
    }
}
