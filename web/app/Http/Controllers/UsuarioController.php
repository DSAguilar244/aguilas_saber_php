<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $query = Usuario::with('roles');

        /** @var \App\Models\Usuario $current */
        $current = Auth::user();
        $isAdmin = $current->roles->contains('name', 'admin');
        
        if (!$isAdmin) {
            $query->where('id', $current->id);
        }

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

    public function buscar(Request $request)
    {
        $search = $request->input('search');

        $usuarios = Usuario::with('roles')
            ->where(function ($q) use ($search) {
                $q->where('nombre', 'ILIKE', "%{$search}%")
                    ->orWhere('apellido', 'ILIKE', "%{$search}%")
                    ->orWhere('email', 'ILIKE', "%{$search}%");
            })
            ->orderBy('id', 'desc')
            ->get(['id', 'nombre', 'apellido', 'email', 'telefono', 'activo']);

        return response()->json($usuarios);
    }

    public function validarNombre(Request $request)
    {
        $existe = Usuario::where('nombre', $request->nombre)->exists();
        return response()->json(['existe' => $existe]);
    }

    public function validarEmail(Request $request)
    {
        $existe = Usuario::where('email', $request->email)->exists();
        return response()->json(['existe' => $existe]);
    }

    public function create()
    {
        $this->authorizeAdmin();
        $roles = Role::all();
        return view('usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'nombre' => 'required|string|max:50',
            'apellido' => 'required|string|max:50',
            'email' => 'required|email|max:100|unique:usuarios,email',
            'telefono' => 'nullable|string|max:20|regex:/^[0-9+\s\-()]*$/|unique:usuarios,telefono',
            'password' => 'required|string|min:8|max:30',
            'rol' => 'required|exists:roles,id',
            'activo' => 'nullable|in:0,1',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'apellido.required' => 'El apellido es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'telefono.regex' => 'El teléfono solo puede contener números, espacios y los caracteres + - ( ).',
            'telefono.unique' => 'Este teléfono ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos :min caracteres.',
            'rol.required' => 'Debes seleccionar un rol.',
            'rol.exists' => 'El rol seleccionado no es válido.',
        ]);

        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'password' => Hash::make($request->password),
            'activo' => $request->activo == '1',
        ]);

        $rol = Role::findOrFail($request->rol);
        $usuario->syncRoles([$rol->name]);

        AuditoriaController::registrar('usuarios', 'crear', $usuario->id, [
            'nombre' => $usuario->nombre,
            'apellido' => $usuario->apellido,
            'email' => $usuario->email,
            'rol' => $rol->name,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente');
    }

    public function edit(Usuario $usuario)
    {
        $this->authorizeEdit($usuario);

        $roles = Role::all();
        $usuario->load('roles');
        return view('usuarios.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, Usuario $usuario)
    {
        $this->authorizeEdit($usuario);

        $request->validate([
            'nombre' => 'required|string|max:50',
            'apellido' => 'required|string|max:50',
            'email' => 'required|email|max:100|unique:usuarios,email,' . $usuario->id,
            'telefono' => 'nullable|string|max:20|regex:/^[0-9+\s\-()]*$/|unique:usuarios,telefono,' . $usuario->id,
            'password' => 'nullable|string|min:8|max:30',
            'rol' => 'required|exists:roles,id',
            'activo' => 'nullable|in:0,1',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'apellido.required' => 'El apellido es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'telefono.regex' => 'El teléfono solo puede contener números, espacios y los caracteres + - ( ).',
            'telefono.unique' => 'Este teléfono ya está registrado.',
            'password.min' => 'La contraseña debe tener al menos :min caracteres.',
            'rol.required' => 'Debes seleccionar un rol.',
            'rol.exists' => 'El rol seleccionado no es válido.',
        ]);

        // Prevenir que el admin se desactive a sí mismo
        if (Auth::id() === $usuario->id && $request->activo != '1') {
            return redirect()->back()->with('error', 'No puedes desactivar tu propia cuenta.')->withInput();
        }

        $usuario->update([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'activo' => $request->activo == '1',
        ]);

        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
            $usuario->save();
        }

        $rol = Role::findOrFail($request->rol);
        $usuario->syncRoles([$rol->name]);

        AuditoriaController::registrar('usuarios', 'actualizar', $usuario->id, [
            'nombre' => $usuario->nombre,
            'apellido' => $usuario->apellido,
            'email' => $usuario->email,
            'rol' => $rol->name,
            'activo' => $usuario->activo,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente');
    }

    public function destroy(Usuario $usuario)
    {
        $this->authorizeAdmin();
        
        // Prevenir que el admin elimine su propia cuenta
        if (Auth::id() === $usuario->id) {
            return redirect()->route('usuarios.index')->with('error', 'No puedes eliminar tu propia cuenta.');
        }
        
        $detalles = [
            'nombre' => $usuario->nombre,
            'apellido' => $usuario->apellido,
            'email' => $usuario->email,
        ];
        $usuario->roles()->detach();
        $usuario->delete();

        AuditoriaController::registrar('usuarios', 'eliminar', $usuario->id, $detalles);

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente');
    }

    private function authorizeAdmin(): void
    {
        $user = Auth::user();
        if (!$user || !($user->roles->contains('name', 'admin'))) {
            abort(403, 'Acceso denegado');
        }
    }

    private function authorizeEdit(Usuario $usuario): void
    {
        $current = Auth::user();
        if ($current && ($current->roles->contains('name', 'admin'))) {
            return;
        }

        if (!$current || $current->id !== $usuario->id) {
            abort(403, 'No tienes permiso para editar este usuario');
        }
    }
}