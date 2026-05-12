<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
            'password'   => 'required|string',
            'role'       => 'required|string|in:administrador,auxiliar,docente,estudiante',
        ]);

        $identifier = trim($request->input('identifier'));
        $role       = $request->input('role');

        // DNI: exactamente 8 dígitos numéricos; lo demás se trata como email
        $field = preg_match('/^\d{8}$/', $identifier) ? 'dni' : 'email';

        $user = User::where($field, $identifier)->first();

        if (! $user || ! Hash::check($request->input('password'), $user->password)) {
            return back()
                ->withInput($request->only('identifier', 'role'))
                ->withErrors(['identifier' => 'Las credenciales no son correctas.']);
        }

        if ($user->user_type !== $role) {
            return back()
                ->withInput($request->only('identifier', 'role'))
                ->withErrors(['identifier' => 'Este usuario no pertenece al perfil "' . ucfirst($role) . '".']);
        }

        if (! $user->activo) {
            return back()
                ->withInput($request->only('identifier', 'role'))
                ->withErrors(['identifier' => 'Su cuenta está desactivada. Contacte al administrador.']);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // Forzar cambio de contraseña en primer login
        if ($user->must_change_password) {
            return redirect()->route('password.change');
        }

        // Estudiantes van a su portal propio
        if ($user->isEstudiante()) {
            return redirect()->route('estudiante.dashboard');
        }

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
