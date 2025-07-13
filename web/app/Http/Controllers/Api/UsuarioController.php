<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index()
    {
        return response()->json(Usuario::orderBy('id', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string',
            'apellido' => 'required|string',
            'email' => 'required|email|unique:usuarios,email',
            'telefono' => 'required|string',
            'activo' => 'required|boolean',
            'password' => 'required|string|min:6',
            'roles' => 'required|array'
        ]);

        $validated['password'] = bcrypt($validated['password']);

        $usuario = Usuario::create($validated);

        if (method_exists($usuario, 'assignRole')) {
            $usuario->assignRole($validated['roles']);
        }

        return response()->json($usuario, 201);
    }

    public function show($id)
    {
        $usuario = Usuario::findOrFail($id);
        return response()->json($usuario);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre' => 'required|string',
            'apellido' => 'required|string',
            'email' => 'required|email|unique:usuarios,email,' . $id,
            'telefono' => 'required|string',
            'activo' => 'required|boolean',
        ]);

        $usuario = Usuario::findOrFail($id);
        $usuario->update($validated);

        return response()->json($usuario);
    }

    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();

        return response()->json(null, 204);
    }
}
