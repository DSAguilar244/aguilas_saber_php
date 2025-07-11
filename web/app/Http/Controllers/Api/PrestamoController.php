<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Prestamo;
use Illuminate\Http\Request;

class PrestamoController extends Controller
{
    public function index()
    {
        return response()->json(
            Prestamo::with(['usuario', 'recurso'])->orderBy('id', 'desc')->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo' => 'required|string',
            'usuario_id' => 'required|exists:usuarios,id',
            'recurso_id' => 'required|exists:recursos,id',
            'fecha_prestamo' => 'required|date',
            'fecha_devolucion' => 'required|date|after_or_equal:fecha_prestamo',
            'estado' => 'required|string',
        ]);

        $prestamo = Prestamo::create($validated);

        return response()->json($prestamo, 201);
    }

    public function show($id)
    {
        $prestamo = Prestamo::with(['usuario', 'recurso'])->findOrFail($id);
        return response()->json($prestamo);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'codigo' => 'required|string',
            'usuario_id' => 'required|exists:usuarios,id',
            'recurso_id' => 'required|exists:recursos,id',
            'fecha_prestamo' => 'required|date',
            'fecha_devolucion' => 'required|date|after_or_equal:fecha_prestamo',
            'estado' => 'required|string',
        ]);

        $prestamo = Prestamo::findOrFail($id);
        $prestamo->update($validated);

        return response()->json($prestamo);
    }

    public function destroy($id)
    {
        $prestamo = Prestamo::findOrFail($id);
        $prestamo->delete();

        return response()->json(null, 204);
    }
}
