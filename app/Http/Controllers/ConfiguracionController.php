<?php

namespace App\Http\Controllers;

use App\Helpers\ConfiguracionColegio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConfiguracionController extends Controller
{
    public function index()
    {
        $cfg        = ConfiguracionColegio::get();
        $firmaUrl   = ConfiguracionColegio::firmaUrl();
        $mesesTodos = ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
                       'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];

        return view('configuracion.index', compact('cfg', 'firmaUrl', 'mesesTodos'));
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'mes_inicio' => 'required|integer|min:1|max:12',
            'mes_fin'    => 'required|integer|min:1|max:12',
        ]);

        if ((int)$request->mes_inicio > (int)$request->mes_fin) {
            return back()->with('error', 'El mes de inicio no puede ser mayor al mes de fin.');
        }

        ConfiguracionColegio::set([
            'mes_inicio' => (int)$request->mes_inicio,
            'mes_fin'    => (int)$request->mes_fin,
        ]);

        return back()->with('success', 'Configuración de meses guardada correctamente.');
    }

    public function subirFirma(Request $request)
    {
        $request->validate([
            'firma' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        // Eliminar firma anterior
        $cfg = ConfiguracionColegio::get();
        if (!empty($cfg['firma_path'])) {
            Storage::disk('public')->delete($cfg['firma_path']);
        }

        $path = $request->file('firma')->storeAs('documentos', 'firma_director.png', 'public');
        ConfiguracionColegio::set(['firma_path' => $path]);

        return back()->with('success', 'Firma del director cargada correctamente.');
    }

    public function eliminarFirma()
    {
        $cfg = ConfiguracionColegio::get();
        if (!empty($cfg['firma_path'])) {
            Storage::disk('public')->delete($cfg['firma_path']);
        }
        ConfiguracionColegio::set(['firma_path' => null]);
        return back()->with('success', 'Firma eliminada.');
    }
}
