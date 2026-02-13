<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Service; // 👈 IMPORTANTE AGREGAR
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::where('user_id', auth()->id())
            ->with('service') // 👈 para evitar problemas al mostrar el servicio
            ->orderBy('created_at', 'desc')
            ->paginate(3);

        $services = Service::all(); // 👈 ESTA VARIABLE FALTABA

        return view('cliente.comentarios', compact('reviews', 'services'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'service_id' => 'required|exists:services,id', // 👈 NUEVO
            'comment' => 'required|string|max:1000',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        Review::create([
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email,
            'service_id' => $data['service_id'], // 👈 NUEVO
            'comment' => $data['comment'],
            'rating' => $data['rating'] ?? null,
        ]);

        return redirect()->route('cliente.comentarios')
            ->with('success', 'Gracias por tu comentario.');
    }
}
