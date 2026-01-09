<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    // 📋 Listar roles
    public function index()
    {
        return response()->json(Role::orderBy('id', 'desc')->get());
    }

    // 📝 Crear rol
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'guard_name' => 'nullable|string',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => $validated['guard_name'] ?? 'api',
            'descripcion' => $validated['descripcion'] ?? null,
        ]);

        return response()->json($role, 201);
    }

    // 🔍 Mostrar rol por ID
    public function show($id)
    {
        $role = Role::findOrFail($id);
        return response()->json($role);
    }

    // ✏️ Actualizar rol
    public function update(Request $request, $id)
    {
        $guard = $request->input('guard_name', 'api');

        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $id . ',id,guard_name,' . $guard,
            'guard_name' => 'nullable|string',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $role = Role::findOrFail($id);
        $role->update([
            'name' => $validated['name'],
            'guard_name' => $validated['guard_name'] ?? 'api',
            'descripcion' => $validated['descripcion'] ?? null,
        ]);

        return response()->json($role);
    }

    // ❌ Eliminar rol
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json(null, 204);
    }
}