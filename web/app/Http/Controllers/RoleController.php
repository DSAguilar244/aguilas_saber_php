<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{

    public function index(Request $request)
    {
        $query = Role::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('descripcion', 'LIKE', "%{$search}%");
            });
        }

        $roles = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        return view('roles.index', compact('roles'));
    }

    public function buscar(Request $request)
    {
        $search = $request->input('search');

        $query = Role::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('descripcion', 'LIKE', "%{$search}%");
            });
        }

        $roles = $query->orderBy('id', 'desc')->get(['id', 'name', 'descripcion']);

        return response()->json($roles);
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'descripcion' => 'nullable|string',
        ], [
            'name.required' => 'El nombre del rol es obligatorio.',
            'name.unique' => 'Este nombre de rol ya existe.',
        ]);

        $role = Role::create($request->only('name', 'descripcion'));

        AuditoriaController::registrar('roles', 'crear', $role->id, [
            'name' => $role->name,
        ]);

        return redirect()->route('roles.index')->with('success', 'Rol creado correctamente');
    }

    public function show($id)
    {
        $role = Role::findOrFail($id);
        return view('roles.show', compact('role'));
    }

    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
            'descripcion' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ], [
            'name.required' => 'El nombre del rol es obligatorio.',
            'name.unique' => 'Este nombre de rol ya existe.',
        ]);

        $role = Role::findOrFail($id);
        $role->update($request->only('name', 'descripcion'));

        // Sincronizar permisos (solo para roles que no sean admin)
        if ($role->name !== 'admin') {
            $permissions = $request->input('permissions', []);
            $role->syncPermissions($permissions);
        }

        AuditoriaController::registrar('roles', 'actualizar', $role->id, [
            'name' => $role->name,
            'permisos' => $request->input('permissions', []),
        ]);

        return redirect()->route('roles.index')->with('success', 'Rol y permisos actualizados correctamente');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $detalles = ['name' => $role->name];
        $role->delete();

        AuditoriaController::registrar('roles', 'eliminar', $role->id, $detalles);

        return redirect()->route('roles.index')->with('success', 'Rol eliminado correctamente');
    }
}