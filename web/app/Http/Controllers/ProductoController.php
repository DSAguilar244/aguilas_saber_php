<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Producto::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'ILIKE', "%$search%")
                    ->orWhere('estado', 'ILIKE', "%$search%");
            });
        }

        $productos = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        return view('productos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'estado' => 'required',
            'fecha_entrada' => 'required|date',
            'fecha_salida' => 'required|date|after_or_equal:fecha_entrada',
            'cantidad' => 'required|integer|min:0',
        ]);

        Producto::create($request->all());

        return redirect()->route('productos.index')->with('success', 'Producto creado correctamente');
    }

    public function show($id)
    {
        $producto = Producto::findOrFail($id);
        return view('productos.show', compact('producto'));
    }

    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        return view('productos.edit', compact('producto'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required',
            'estado' => 'required',
            'fecha_entrada' => 'required|date',
            'fecha_salida' => 'required|date|after_or_equal:fecha_entrada',
            'cantidad' => 'required|integer|min:0',
        ]);

        $producto = Producto::findOrFail($id);
        $producto->update($request->all());

        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente');
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return redirect()->route('productos.index')->with('success', 'Producto eliminado correctamente');
    }
}
