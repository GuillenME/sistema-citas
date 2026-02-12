<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $homeSetting = HomeSetting::firstOrCreate([], [
            'hero_title' => 'BARBERíA & SPA',
            'hero_subtitle' => 'Estilo, cuidado y bienestar en un solo lugar',
            'feature_1_title' => 'Cortes Modernos',
            'feature_1_description' => 'Técnicas actuales y tendencias',
            'feature_2_title' => 'Tratamientos Spa',
            'feature_2_description' => 'Relajación y cuidado personal',
            'feature_3_title' => 'Atención Personalizada',
            'feature_3_description' => 'Productos de primera línea',
            'footer_address' => 'Calle Principal #123 - Guadalajara',
            'footer_phone' => '33 1234 5678',
            'footer_hours' => 'Lun-Sab 9:00-20:00',
        ]);

        return view('admin.home.edit', compact('homeSetting'));
    }

    public function update(Request $request)
    {
        $homeSetting = HomeSetting::first();

        $data = $request->validate([
            'hero_title' => 'required|string|min:3|max:255',
            'hero_subtitle' => 'required|string|max:255',
            'feature_1_title' => 'required|string|max:255',
            'feature_1_description' => 'required|string',
            'feature_2_title' => 'required|string|max:255',
            'feature_2_description' => 'required|string',
            'feature_3_title' => 'required|string|max:255',
            'feature_3_description' => 'required|string',
            'hero_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'navbar_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'footer_address' => 'nullable|string|max:255',
            'footer_phone' => 'nullable|string|max:100',
            'footer_hours' => 'nullable|string|max:100',
        ]);

        if ($request->hasFile('hero_image')) {
            if ($homeSetting && $homeSetting->hero_image) {
                Storage::disk('public')->delete($homeSetting->hero_image);
            }

            $data['hero_image'] = $request->file('hero_image')
                ->store('home_settings', 'public');
        }

        if ($request->hasFile('navbar_logo')) {
            if ($homeSetting && $homeSetting->navbar_logo) {
                Storage::disk('public')->delete($homeSetting->navbar_logo);
            }

            $data['navbar_logo'] = $request->file('navbar_logo')
                ->store('home_settings', 'public');
        }
        $homeSetting->update($data);

        return redirect()->route('admin.home_settings.edit')
            ->with('success', 'Configuración de inicio actualizada correctamente');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}


