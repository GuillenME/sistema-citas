<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(3);

        return view('cliente.comentarios', compact('reviews'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'comment' => 'required|string|max:1000',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        Review::create([
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email,
            'comment' => $data['comment'],
            'rating' => $data['rating'] ?? null,
        ]);

        return redirect()->route('cliente.comentarios')
            ->with('success', 'Gracias por tu comentario.');
    }
}
