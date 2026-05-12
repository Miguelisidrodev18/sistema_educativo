<?php

namespace App\Http\Controllers;

use App\Models\DocenteAsignacion;
use App\Models\Sede;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('sede')->orderBy('user_type')->orderBy('name')->get();
        return view('users.index', compact('users'));
    }

    public function carnets(Request $request)
    {
        $query = User::with('sede')->where('activo', true);

        if ($request->filled('tipo')) {
            $query->where('user_type', $request->tipo);
        }
        if ($request->filled('sede_id')) {
            $query->where('sede_id', $request->sede_id);
        }

        $users = $query->orderBy('user_type')->orderBy('name')->get();
        $sedes = Sede::where('activo', true)->orderBy('nombre')->get();

        return view('users.carnets', compact('users', 'sedes'));
    }

    public function create()
    {
        $sedes = Sede::where('activo', true)->orderBy('nombre')->get();
        return view('users.create', compact('sedes'));
    }

    public function store(Request $request)
    {
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

    public function generarQr(User $user)
    {
        $this->generarQrUser($user);
        return back()->with('success', "QR generado para {$user->name}.");
    }

    public function generarQrTodos()
    {
        $users = User::whereIn('user_type', ['docente','auxiliar'])
            ->where('activo', true)
            ->get();
        $count = 0;
        foreach ($users as $user) {
            $this->generarQrUser($user);
            $count++;
        }
        return back()->with('success', "QR generados para {$count} docente(s)/auxiliar(es).");
    }

    private function generarQrUser(User $user): void
    {
        try {
            $qrContent = "USR:{$user->id}";
            $qrDir = storage_path('app/public/qr_users');
            if (!is_dir($qrDir)) mkdir($qrDir, 0755, true);
            $filename = "qr_usr_{$user->id}.svg";
            QrCode::format('svg')->size(200)->generate($qrContent, "{$qrDir}/{$filename}");
            $user->update(['qr_code_path' => "qr_users/{$filename}"]);
        } catch (\Exception $e) {
            // non-critical
        }
    }

    public function show(User $user)
    {
        $user->load(['asignaciones.sede', 'sede']);
        $sedes = Sede::where('activo', true)->orderBy('nombre')->get();
        return view('users.show', compact('user', 'sedes'));
    }

    public function storeAsignacion(Request $request, User $user)
    {
        $data = $request->validate([
            'sede_id'      => 'nullable|exists:sedes,id',
            'nivel'        => 'required|in:inicial,primaria,secundaria',
            'grado_seccion'=> 'required|string|max:30',
            'materia'      => 'nullable|string|max:60',
        ]);

        $data['user_id'] = $user->id;

        DocenteAsignacion::firstOrCreate(
            [
                'user_id'      => $user->id,
                'nivel'        => $data['nivel'],
                'grado_seccion'=> $data['grado_seccion'],
                'materia'      => $data['materia'] ?? null,
            ],
            ['sede_id' => $data['sede_id'] ?? null]
        );

        return back()->with('success', 'Asignación agregada.');
    }

    public function destroyAsignacion(User $user, DocenteAsignacion $asignacion)
    {
        abort_if($asignacion->user_id !== $user->id, 403);
        $asignacion->delete();
        return back()->with('success', 'Asignación eliminada.');
    }

    public function toggle(User $user)
    {
        $user->update(['activo' => ! $user->activo]);
        $estado = $user->activo ? 'activado' : 'desactivado';
        return back()->with('success', "Usuario \"{$user->name}\" {$estado}.");
    }
}
