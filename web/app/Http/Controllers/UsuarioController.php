<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Usuario::query();

        // Búsqueda por nombre, apellido o email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'ILIKE', "%$search%")
                    ->orWhere('apellido', 'ILIKE', "%$search%")
                    ->orWhere('email', 'ILIKE', "%$search%");
            });
        }

        $usuarios = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        return view('usuarios.index', compact('usuarios'));
    }
    public function create()
    {
        $roles = Role::all();
        return view('usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'email' => 'required|email|unique:usuarios,email',
            'telefono' => 'nullable',
            'password' => 'required|min:6',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
            'activo' => 'nullable|in:0,1',
        ]);

        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'password' => Hash::make($request->password),
            'activo' => $request->activo == '1',
        ]);

        $usuario->roles()->sync($request->roles);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente');
    }

    public function edit(Usuario $usuario)
    {
        $roles = Role::all();
        $usuario->load('roles');
        return view('usuarios.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, Usuario $usuario)
    {
        $request->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'email' => 'required|email|unique:usuarios,email,' . $usuario->id,
            'telefono' => 'nullable',
            'password' => 'nullable|min:6',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
            'activo' => 'nullable|in:0,1',
        ]);

        $usuario->nombre = $request->nombre;
        $usuario->apellido = $request->apellido;
        $usuario->email = $request->email;
        $usuario->telefono = $request->telefono;
        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }
        $usuario->activo = $request->activo == '1';
        $usuario->save();

        $usuario->roles()->sync($request->roles);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente');
    }

    public function destroy(Usuario $usuario)
    {
        $usuario->roles()->detach();
        $usuario->delete();
        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente');
    }
}
