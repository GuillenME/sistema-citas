<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminServicioController extends Controller
{
    public function downloadTemplate()
    {
        $headers = ['nombre', 'descripcion', 'duracion_minutos', 'precio', 'activo'];
        $rows = [
            ['Corte clasico', 'Corte basico para caballero', 30, 150, 1],
            ['Corte y barba', 'Paquete completo corte y barba', 60, 280, 1],
        ];

        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, $headers);
        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        $filename = 'plantilla_servicios.csv';

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }

    public function importCsv(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $path = $request->file('file')->getRealPath();
        $handle = fopen($path, 'r');
        if (!$handle) {
            return back()->with('error', 'No se pudo abrir el archivo.');
        }

        $firstLine = fgets($handle);
        if ($firstLine === false) {
            fclose($handle);
            return back()->with('error', 'El archivo está vacío.');
        }

        $delimiter = substr_count($firstLine, ';') > substr_count($firstLine, ',') ? ';' : ',';
        rewind($handle);

        $header = fgetcsv($handle, 0, $delimiter);
        if (!$header) {
            fclose($handle);
            return back()->with('error', 'No se encontró encabezado en el CSV.');
        }

        $header = array_map(function ($h) {
            return Str::slug(trim($h), '_');
        }, $header);

        $map = [
            'nombre' => 'name',
            'name' => 'name',
            'descripcion' => 'description',
            'description' => 'description',
            'duracion_minutos' => 'duration_minutes',
            'duration_minutes' => 'duration_minutes',
            'precio' => 'price',
            'price' => 'price',
            'activo' => 'active',
            'active' => 'active',
        ];

        if (!in_array('nombre', $header) && !in_array('name', $header)) {
            fclose($handle);
            return back()->with('error', 'El CSV debe incluir la columna "nombre".');
        }

        $created = 0;
        $errors = 0;

        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            if (count(array_filter($row)) === 0) {
                continue;
            }

            $data = [];
            foreach ($header as $index => $key) {
                if (!isset($map[$key])) {
                    continue;
                }
                $data[$map[$key]] = $row[$index] ?? null;
            }

            if (empty($data['name'])) {
                $errors++;
                continue;
            }

            $data['duration_minutes'] = (int) ($data['duration_minutes'] ?? 0);
            $data['price'] = (float) ($data['price'] ?? 0);
            $data['active'] = isset($data['active']) && $data['active'] !== ''
                ? (int) $data['active']
                : 1;

            if ($data['duration_minutes'] < 5 || $data['price'] < 0) {
                $errors++;
                continue;
            }

            Servicio::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'duration_minutes' => $data['duration_minutes'],
                'price' => $data['price'],
                'active' => $data['active'],
            ]);

            $created++;
        }

        fclose($handle);

        $message = "Importación finalizada. Creados: $created.";
        if ($errors > 0) {
            $message .= " Filas omitidas: $errors.";
        }

        return redirect()->route('admin.servicios.index')->with('success', $message);
    }

    public function index()
    {
        $servicios = Servicio::all();
        return view('admin.servicios.index', compact('servicios'));
    }

    public function create()
    {
        return view('admin.servicios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|min:3',
            'descripcion' => 'nullable|string',
            'duracion_minutos' => 'required|integer|min:5',
            'precio' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('servicios', 'public');
        }

        Servicio::create([
            'name' => $request->nombre,
            'description' => $request->descripcion,
            'duration_minutes' => $request->duracion_minutos,
            'price' => $request->precio,
            'active' => 1,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.servicios.index')
            ->with('success', 'Servicio creado correctamente');
    }

public function edit(Servicio $servicio)
    {
        return view('admin.servicios.edit', compact('servicio'));
    }

    public function update(Request $request, Servicio $servicio)
    {
        $data = $request->validate([
            'nombre' => 'required|string|min:3',
            'descripcion' => 'nullable|string',
            'duracion_minutos' => 'required|integer|min:5',
            'precio' => 'required|numeric|min:0',
            'activo' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($servicio->image) {
                Storage::disk('public')->delete($servicio->image);
            }

            $data['image'] = $request->file('image')
                ->store('servicios', 'public');
        }

        $servicio->update([
            'name' => $request->nombre,
            'description' => $request->descripcion,
            'duration_minutes' => $request->duracion_minutos,
            'price' => $request->precio,
            'active' => $request->activo,
            'image' => $data['image'] ?? $servicio->image,
        ]);

        return redirect()->route('admin.servicios.index')
            ->with('success', 'Servicio actualizado');
    }

    public function destroy(Servicio $servicio)
    {
        if ($servicio->image) {
            Storage::disk('public')->delete($servicio->image);
        }

        $servicio->update(['active' => 0]);

        return redirect()->route('admin.servicios.index')
            ->with('success', 'Servicio desactivado');
    }

}
