<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\MatriculaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SedeController;
use App\Http\Controllers\ConfiguracionController;

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
        $sedeId = session('sede_id');
        $sedes  = \App\Models\Sede::where('activo', true)->withCount('alumnos')->orderBy('nombre')->get();

        $alumnosQ    = \App\Models\Alumno::where('activo', true);
        $matriculasQ = \App\Models\Matricula::where('periodo', date('Y'));
        $pagosHoyQ   = \App\Models\PagoPension::whereDate('fecha_pago', today());
        $recaudadoQ  = \App\Models\PagoPension::whereMonth('fecha_pago', now()->month)->whereYear('fecha_pago', now()->year);
        $asistQ      = \App\Models\Asistencia::whereDate('fecha', today())->where('estado', 'PRESENTE');

        if ($sedeId) {
            $alumnosQ->where('sede_id', $sedeId);
            $matriculasQ->where('sede_id', $sedeId);
            $alumnosIdsSede = \App\Models\Alumno::where('sede_id', $sedeId)->pluck('id');
            $pagosHoyQ->whereIn('alumno_id', $alumnosIdsSede);
            $recaudadoQ->whereIn('alumno_id', $alumnosIdsSede);
            $asistQ->whereIn('alumno_id', $alumnosIdsSede);
        }

        // Distribución por nivel/grado para la sede activa
        $distribucion = \App\Models\Alumno::where('activo', true)
            ->when($sedeId, fn($q) => $q->where('sede_id', $sedeId))
            ->whereNotNull('nivel_academico')
            ->selectRaw('nivel_academico, grado_seccion, COUNT(*) as total')
            ->groupBy('nivel_academico', 'grado_seccion')
            ->orderBy('nivel_academico')
            ->orderBy('grado_seccion')
            ->get()
            ->groupBy('nivel_academico');

        $stats = [
            'total_alumnos'   => $alumnosQ->count(),
            'total_docentes'  => \App\Models\User::whereIn('user_type', ['docente','auxiliar'])->where('activo', true)->count(),
            'matriculas_anio' => $matriculasQ->count(),
            'pagos_hoy'       => $pagosHoyQ->count(),
            'recaudado_mes'   => $recaudadoQ->sum('monto'),
            'asistencia_hoy'  => $asistQ->count(),
        ];

        $sedeActiva = $sedeId ? \App\Models\Sede::find($sedeId) : null;

        return view('dashboard', compact('stats', 'sedes', 'sedeActiva', 'distribucion'));
    })->name('dashboard');

    // Alumnos
    Route::get('/alumnos/carnets',    [AlumnoController::class, 'carnets'])->name('alumnos.carnets');
    Route::post('/alumnos/generar-qr',[AlumnoController::class, 'generarQrMasivo'])->name('alumnos.generar-qr');
    Route::resource('alumnos', AlumnoController::class);
    Route::get('/alumnos/{alumno}/constancia',     [AlumnoController::class, 'constanciaHtml'])->name('alumnos.constancia');
    Route::get('/alumnos/{alumno}/ticket-pdf',    [AlumnoController::class, 'ticketPdf'])->name('alumnos.ticket-pdf');
    Route::get('/alumnos/{alumno}/constancia-pdf',[AlumnoController::class, 'constanciaPdf'])->name('alumnos.constancia-pdf');

    // Matrículas (rutas específicas ANTES del resource para evitar conflicto con {matricula})
    Route::get('/matriculas/plantilla/{nivel}', [MatriculaController::class, 'plantilla'])->name('matriculas.plantilla')->where('nivel','inicial|primaria|secundaria');
    Route::post('/matriculas/importar',  [MatriculaController::class, 'importar'])->name('matriculas.importar');
    Route::resource('matriculas', MatriculaController::class)->only(['index', 'create', 'store', 'show']);

    // Pagos
    Route::get('/pagos',                           [PagoController::class, 'index'])->name('pagos.index');
    Route::get('/pagos/alumno/{alumno}',            [PagoController::class, 'alumno'])->name('pagos.alumno');
    Route::post('/pagos/alumno/{alumno}/registrar', [PagoController::class, 'registrarPension'])->name('pagos.registrar');
    Route::post('/pagos/importar',                  [PagoController::class, 'importar'])->name('pagos.importar');

    // Asistencias
    Route::get('/asistencias',                           [AsistenciaController::class, 'index'])->name('asistencias.index');
    Route::post('/asistencias/registrar',                [AsistenciaController::class, 'registrar'])->name('asistencias.registrar');
    Route::post('/asistencias/registrar-masivo',         [AsistenciaController::class, 'registrarMasivo'])->name('asistencias.registrar-masivo');
    Route::get('/asistencias/docentes',                  [AsistenciaController::class, 'docentes'])->name('asistencias.docentes');
    Route::post('/asistencias/docentes/registrar',       [AsistenciaController::class, 'registrarDocente'])->name('asistencias.registrar-docente');
    Route::post('/asistencias/docentes/registrar-masivo',[AsistenciaController::class, 'registrarMasivoDocentes'])->name('asistencias.registrar-masivo-docentes');
    Route::get('/asistencias/qr',                        [AsistenciaController::class, 'qr'])->name('asistencias.qr');
    Route::post('/asistencias/qr/registrar',             [AsistenciaController::class, 'registrarQr'])->name('asistencias.registrar-qr');

    // Sedes (solo admin)
    Route::get('/sedes',                         [SedeController::class, 'index'])->name('sedes.index');
    Route::post('/sedes',                         [SedeController::class, 'store'])->name('sedes.store');
    Route::put('/sedes/{sede}',                   [SedeController::class, 'update'])->name('sedes.update');
    Route::post('/sedes/{sede}/toggle',           [SedeController::class, 'toggle'])->name('sedes.toggle');
    Route::post('/sedes/{sede}/seleccionar',      [SedeController::class, 'seleccionar'])->name('sedes.seleccionar')->where('sede', '[0-9]+');

    // Configuración (solo admin)
    Route::get('/configuracion',              [ConfiguracionController::class, 'index'])->name('configuracion.index');
    Route::post('/configuracion/guardar',     [ConfiguracionController::class, 'guardar'])->name('configuracion.guardar');
    Route::post('/configuracion/firma',       [ConfiguracionController::class, 'subirFirma'])->name('configuracion.subir-firma');
    Route::delete('/configuracion/firma',     [ConfiguracionController::class, 'eliminarFirma'])->name('configuracion.eliminar-firma');

    // Usuarios (solo admin)
    Route::get('/usuarios',                                [UserController::class, 'index'])->name('users.index');
    Route::get('/usuarios/carnets',                        [UserController::class, 'carnets'])->name('users.carnets');
    Route::get('/usuarios/crear',                          [UserController::class, 'create'])->name('users.create');
    Route::post('/usuarios',                               [UserController::class, 'store'])->name('users.store');
    Route::post('/usuarios/generar-qr-todos',              [UserController::class, 'generarQrTodos'])->name('users.generar-qr-todos');
    Route::get('/usuarios/{user}',                         [UserController::class, 'show'])->name('users.show');
    Route::post('/usuarios/{user}/toggle',                 [UserController::class, 'toggle'])->name('users.toggle');
    Route::post('/usuarios/{user}/generar-qr',             [UserController::class, 'generarQr'])->name('users.generar-qr');
    Route::post('/usuarios/{user}/asignaciones',           [UserController::class, 'storeAsignacion'])->name('users.asignaciones.store');
    Route::delete('/usuarios/{user}/asignaciones/{asignacion}', [UserController::class, 'destroyAsignacion'])->name('users.asignaciones.destroy');
});
