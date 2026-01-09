<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index()
    {
        // 👁 Incluye los roles en la respuesta
        $usuarios = Usuario::with('roles')->orderBy('id', 'desc')->get();
        return response()->json($usuarios);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'    => 'required|string',
            'apellido'  => 'required|string',
            'email'     => 'required|email|unique:usuarios,email',
            'telefono'  => 'required|string',
            'activo'    => 'required|boolean',
            'password'  => 'required|string|min:6',
            'roles'     => 'required|array|min:1',
            'roles.*'   => 'integer|exists:roles,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $usuario = Usuario::create([
            'nombre'   => $validated['nombre'],
            'apellido' => $validated['apellido'],
            'email'    => $validated['email'],
            'telefono' => $validated['telefono'],
            'activo'   => $validated['activo'],
            'password' => $validated['password'],
        ]);

        $roles = Role::whereIn('id', $validated['roles'])->get();

        if ($roles->count() !== count($validated['roles'])) {
            return response()->json([
                'message' => 'Uno o más roles seleccionados no existen o no tienen guard válido.'
            ], 422);
        }

        $usuario->syncRoles($roles); // ✅ Asigna roles reales

        $usuario->load('roles'); // ✅ Incluye los roles en la respuesta

        return response()->json($usuario, 201);
    }

    public function show($id)
    {
        $usuario = Usuario::with('roles')->findOrFail($id);
        return response()->json($usuario);
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $validated = $request->validate([
            'nombre'   => 'required|string',
            'apellido' => 'required|string',
            'email'    => 'required|email|unique:usuarios,email,' . $usuario->id,
            'telefono' => 'required|string',
            'activo'   => 'required|boolean',
            'password' => 'nullable|string|min:6',
            'roles'    => 'nullable|array',
            'roles.*'  => 'integer|exists:roles,id',
        ]);

        $usuario->update([
            'nombre'   => $validated['nombre'],
            'apellido' => $validated['apellido'],
            'email'    => $validated['email'],
            'telefono' => $validated['telefono'],
            'activo'   => $validated['activo'],
        ]);

        if (!empty($validated['password'])) {
            $usuario->password = Hash::make($validated['password']);
            $usuario->save();
        }

        if ($request->has('roles')) {
            if (!empty($validated['roles'])) {
                $roles = Role::whereIn('id', $validated['roles'])->get();

                if ($roles->count() !== count($validated['roles'])) {
                    return response()->json([
                        'message' => 'Uno o más roles seleccionados no existen o no tienen guard válido.'
                    ], 422);
                }

                $usuario->syncRoles($roles);
            } else {
                $usuario->syncRoles([]);
            }
        }

        $usuario->load('roles');

        return response()->json($usuario);
    }

    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->roles()->detach(); // ✅ Limpia los roles asignados
        $usuario->delete();

        return response()->json(null, 204);
    }
}