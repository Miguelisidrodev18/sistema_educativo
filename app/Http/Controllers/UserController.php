<?php

namespace App\Http\Controllers;

use App\Models\Sede;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('sede')->orderBy('user_type')->orderBy('name')->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $sedes = Sede::where('activo', true)->orderBy('nombre')->get();
        return view('users.create', compact('sedes'));
    }

    public function store(Request $request)
    {
        if ($request->input('master_key') !== env('MASTER_KEY')) {
            return back()
                ->withInput($request->except('master_key', 'password', 'password_confirmation'))
                ->withErrors(['master_key' => 'Clave maestra incorrecta. No se creó el usuario.']);
        }

        $data = $request->validate([
            'name'                  => 'required|string|max:100',
            'dni'                   => 'required|digits:8|unique:users,dni',
            'email'                 => 'required|email|unique:users,email',
            'password'              => ['required', 'confirmed', Password::min(8)],
            'user_type'             => 'required|in:administrador,auxiliar,docente,estudiante',
            'sede_id'               => 'nullable|exists:sedes,id',
            'activo'                => 'boolean',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['activo']   = $request->boolean('activo', true);

        $user = User::create($data);
        $user->assignRole($data['user_type']);

        return redirect()->route('users.index')
            ->with('success', "Usuario \"{$user->name}\" creado correctamente.");
    }

    public function toggle(User $user)
    {
        $user->update(['activo' => ! $user->activo]);
        $estado = $user->activo ? 'activado' : 'desactivado';
        return back()->with('success', "Usuario \"{$user->name}\" {$estado}.");
    }
}
