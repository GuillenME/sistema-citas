<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeSetting;
use Illuminate\Http\Request;

class LegalContentController extends Controller
{
    public function edit()
    {
        $homeSetting = HomeSetting::firstOrCreate([], HomeSetting::defaultAttributes());

        return view('admin.legal.edit', compact('homeSetting'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'terms_content' => 'required|string',
            'privacy_policy_content' => 'required|string',
        ]);

        $homeSetting = HomeSetting::firstOrCreate([], HomeSetting::defaultAttributes());

        $homeSetting->update([
            'terms_content' => trim($data['terms_content']),
            'privacy_policy_content' => trim($data['privacy_policy_content']),
            'terms_updated_at' => now(),
            'privacy_policy_updated_at' => now(),
        ]);

        return redirect()
            ->route('admin.legal.edit')
            ->with('success', 'Contenido legal actualizado correctamente');
    }
}
