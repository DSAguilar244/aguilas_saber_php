<?php

namespace App\Http\Controllers;

use App\Models\Recurso;
use Illuminate\Http\Request;

class RecursoController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Recurso::query();

        // Búsqueda por nombre, descripción o categoría (ajusta los campos según tu modelo)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'ILIKE', "%$search%")
                    ->orWhere('descripcion', 'ILIKE', "%$search%");
            });
        }

        $recursos = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        return view('recursos.index', compact('recursos'));
    }

    public function create()
    {
        return view('recursos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'nullable',
            'cantidad' => 'required|integer|min:0',
            'estado' => 'required',
        ]);

        Recurso::create($request->all());

        return redirect()->route('recursos.index')->with('success', 'Recurso creado correctamente');
    }

    public function show($id)
    {
        $recurso = Recurso::findOrFail($id);
        return view('recursos.show', compact('recurso'));
    }

    public function edit($id)
    {
        $recurso = Recurso::findOrFail($id);
        return view('recursos.edit', compact('recurso'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'nullable',
            'cantidad' => 'required|integer|min:0',
            'estado' => 'required',
        ]);

        $recurso = Recurso::findOrFail($id);
        $recurso->update($request->all());

        return redirect()->route('recursos.index')->with('success', 'Recurso actualizado correctamente');
    }

    public function destroy($id)
    {
        $recurso = Recurso::findOrFail($id);
        $recurso->delete();

        return redirect()->route('recursos.index')->with('success', 'Recurso eliminado correctamente');
    }
}
