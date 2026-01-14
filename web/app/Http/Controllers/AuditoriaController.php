<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditoriaController extends Controller
{
    /** Registrar evento en auditoría */
    public static function registrar(string $entidad, string $accion, $registroId = null, $detalles = null): void
    {
        $user = Auth::user();
        if (!$user) {
            return;
        }

        Auditoria::create([
            'usuario_id' => $user->id,
            'usuario_email' => $user->email,
            'usuario_nombre' => trim($user->nombre . ' ' . $user->apellido),
            'entidad' => $entidad,
            'accion' => $accion,
            'registro_id' => $registroId,
            'detalles' => is_array($detalles) ? json_encode($detalles) : $detalles,
        ]);
    }

    public function index(Request $request)
    {
        $query = Auditoria::query();

        if ($request->filled('mes')) {
            $query->whereMonth('created_at', $request->mes);
        }

        if ($request->filled('anio')) {
            $query->whereYear('created_at', $request->anio);
        }

        if ($request->filled('entidad')) {
            $query->where('entidad', $request->entidad);
        }

        $auditorias = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('auditorias.index', compact('auditorias'));
    }

    public function limpiar()
    {
        $user = Auth::user();
        if (!$user || !($user->roles->contains('name', 'admin'))) {
            return redirect()->route('auditorias.index')->with('error', 'No tienes permiso para realizar esta acción');
        }

        $count = Auditoria::count();
        Auditoria::truncate();

        return redirect()->route('auditorias.index')->with('success', "Se eliminaron {$count} registros de auditoría");
    }
}
