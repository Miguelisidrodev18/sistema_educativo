<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\MatriculaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\UserController;

// Landing page (public)
Route::get('/', fn() => view('landing'))->name('landing');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated routes
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        $stats = [
            'total_alumnos'   => \App\Models\Alumno::where('activo', true)->count(),
            'total_docentes'  => \App\Models\User::whereIn('user_type', ['docente','auxiliar'])->where('activo', true)->count(),
            'matriculas_anio' => \App\Models\Matricula::where('periodo', date('Y'))->count(),
            'pagos_hoy'       => \App\Models\PagoPension::whereDate('fecha_pago', today())->count(),
            'recaudado_mes'   => \App\Models\PagoPension::whereMonth('fecha_pago', now()->month)->whereYear('fecha_pago', now()->year)->sum('monto'),
            'asistencia_hoy'  => \App\Models\Asistencia::whereDate('fecha', today())->where('estado', 'PRESENTE')->count(),
        ];
        return view('dashboard', compact('stats'));
    })->name('dashboard');

    // Alumnos
    Route::resource('alumnos', AlumnoController::class);
    Route::get('/alumnos/{alumno}/ticket-pdf', [AlumnoController::class, 'ticketPdf'])->name('alumnos.ticket-pdf');
    Route::get('/alumnos/{alumno}/constancia-pdf', [AlumnoController::class, 'constanciaPdf'])->name('alumnos.constancia-pdf');

    // Matrículas
    Route::resource('matriculas', MatriculaController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('/matriculas/importar', [MatriculaController::class, 'importar'])->name('matriculas.importar');

    // Pagos
    Route::get('/pagos', [PagoController::class, 'index'])->name('pagos.index');
    Route::get('/pagos/alumno/{alumno}', [PagoController::class, 'alumno'])->name('pagos.alumno');
    Route::post('/pagos/alumno/{alumno}/registrar', [PagoController::class, 'registrarPension'])->name('pagos.registrar');
    Route::post('/pagos/importar', [PagoController::class, 'importar'])->name('pagos.importar');

    // Usuarios (solo admin)
    Route::get('/usuarios', [UserController::class, 'index'])->name('users.index');
    Route::get('/usuarios/crear', [UserController::class, 'create'])->name('users.create');
    Route::post('/usuarios', [UserController::class, 'store'])->name('users.store');
    Route::post('/usuarios/{user}/toggle', [UserController::class, 'toggle'])->name('users.toggle');

    // Asistencias
    Route::get('/asistencias', [AsistenciaController::class, 'index'])->name('asistencias.index');
    Route::post('/asistencias/registrar', [AsistenciaController::class, 'registrar'])->name('asistencias.registrar');
    Route::post('/asistencias/registrar-masivo', [AsistenciaController::class, 'registrarMasivo'])->name('asistencias.registrar-masivo');
    Route::get('/asistencias/docentes', [AsistenciaController::class, 'docentes'])->name('asistencias.docentes');
    Route::post('/asistencias/docentes/registrar', [AsistenciaController::class, 'registrarDocente'])->name('asistencias.registrar-docente');
    Route::post('/asistencias/docentes/registrar-masivo', [AsistenciaController::class, 'registrarMasivoDocentes'])->name('asistencias.registrar-masivo-docentes');
    Route::get('/asistencias/qr', [AsistenciaController::class, 'qr'])->name('asistencias.qr');
    Route::post('/asistencias/qr/registrar', [AsistenciaController::class, 'registrarQr'])->name('asistencias.registrar-qr');
});
