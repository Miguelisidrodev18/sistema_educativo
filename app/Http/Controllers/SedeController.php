<?php

namespace App\Http\Controllers;

use App\Models\Sede;
use Illuminate\Http\Request;

class SedeController extends Controller
{
    public function index()
    {
        $sedes = Sede::withCount(['alumnos', 'matriculas'])->orderBy('nombre')->get();
        return view('sedes.index', compact('sedes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'    => 'required|string|max:100|unique:sedes,nombre',
            'direccion' => 'nullable|string|max:255',
            'telefono'  => 'nullable|string|max:20',
        ]);
        $data['activo'] = true;
        Sede::create($data);
        return back()->with('success', 'Sede "' . $data['nombre'] . '" creada correctamente.');
    }

    public function update(Request $request, Sede $sede)
    {
        $data = $request->validate([
            'nombre'    => 'required|string|max:100|unique:sedes,nombre,' . $sede->id,
            'direccion' => 'nullable|string|max:255',
            'telefono'  => 'nullable|string|max:20',
            'activo'    => 'boolean',
        ]);
        $data['activo'] = $request->boolean('activo');
        $sede->update($data);
        return back()->with('success', 'Sede actualizada correctamente.');
    }

    public function toggle(Sede $sede)
    {
        $sede->update(['activo' => !$sede->activo]);
        $estado = $sede->activo ? 'activada' : 'desactivada';
        return back()->with('success', "Sede \"{$sede->nombre}\" {$estado}.");
    }

    public function seleccionar(Request $request, $sede)
    {
        if ($sede == 0 || $sede === 'todas') {
            session()->forget('sede_id');
            return back()->with('success', 'Mostrando todas las sedes.');
        }
        $sedeModel = Sede::findOrFail($sede);
        session(['sede_id' => $sedeModel->id]);
        return back()->with('success', "Sede activa: {$sedeModel->nombre}");
    }
}
