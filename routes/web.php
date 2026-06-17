<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EjercicioController;
use App\Http\Controllers\MaquinaController;
use App\Http\Controllers\CalendarioController;
use App\Http\Controllers\SociaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MedidaController;
use App\Http\Controllers\KioskoController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\ReporteAsistenciaController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\DashboardGerenteController;
use App\Http\Controllers\DashboardEntrenadoraController;
use App\Http\Controllers\DashboardSociaController;
use App\Http\Controllers\PerfilSociaController;
use App\Http\Controllers\MensajeController;
use App\Http\Controllers\ProgresoSociaController;
use Illuminate\Support\Facades\Route;

/*╔═══════════════════════════╗
  ║  Rutas SIN autenticación  ║
  ╚═══════════════════════════╝*/

// === index ===
Route::get('/', function () {
    return view('auth.login');
});

// === Kiosko de asistencia (que corre en el gimnasio) ===
Route::get('/kiosko', [KioskoController::class, 'panel'])->name('kiosko.panel');
Route::get('/kiosko/inicio', [KioskoController::class, 'inicio'])->name('kiosko.inicio');
Route::get('/kiosko/calendario-data', [KioskoController::class, 'calendarioData'])->name('kiosko.calendario-data');
Route::post('/kiosko/buscar', [KioskoController::class, 'buscar'])->name('kiosko.buscar');


/*╔══════════════════════════════════════╗
  ║  Rutas protegidas por autenticación  ║
  ╚══════════════════════════════════════╝*/
Route::middleware('auth')->group(function () {

    // === Perfil de usuario ===
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/theme', [ProfileController::class, 'updateTheme'])->name('profile.theme.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // === Dashboards por Rol ===
    Route::get('/dashboard', function () {
        $role = (string) optional(auth()->user())->role;

        return match ($role) {
            'Administrador' => redirect()->route('dashboard.admin'),
            'Gerente' => redirect()->route('dashboard.gerente'),
            'Entrenadora' => redirect()->route('dashboard.entrenadora'),
            'Socia' => redirect()->route('dashboard.socia'),
            default => abort(403, 'Rol no autorizado para dashboard.'),
        };
    })->name('dashboard');
    Route::middleware('role:Administrador')->group(function () {
        Route::get('/dashboard-admin', [DashboardAdminController::class, 'index'])->name('dashboard.admin');
    });
    Route::middleware('role:Gerente')->group(function () {
        Route::get('/dashboard-gerente', [DashboardGerenteController::class, 'index'])->name('dashboard.gerente');
    });
    Route::middleware('role:Entrenadora')->group(function () {
        Route::get('/dashboard-entrenadora', [DashboardEntrenadoraController::class, 'index'])->name('dashboard.entrenadora');
    });
    Route::middleware('role:Socia')->group(function () {
        Route::get('/dashboard-socia', [DashboardSociaController::class, 'index'])->name('dashboard.socia');
        Route::get('/dashboard-socia/calendario-data', [DashboardSociaController::class, 'calendarioData'])->name('dashboard.socia.calendario-data');
    });
    
    // === Perfil de Socia, protegida por autorización RoleMiddleware ===
    Route::middleware('role:Socia')->group(function () {
        Route::get('/mi-perfil', [PerfilSociaController::class, 'show'])->name('perfil-socia.show');
        Route::get('/mi-perfil/editar', [PerfilSociaController::class, 'edit'])->name('perfil-socia.edit');
        Route::match(['put', 'patch'], '/mi-perfil', [PerfilSociaController::class, 'update'])->name('perfil-socia.update');
    });

    // === Mensajería interna (todos los roles) ===
    Route::prefix('mensajes')->name('mensajes.')->group(function () {
        Route::get('/', [MensajeController::class, 'index'])->name('index');
        Route::get('/enviados', [MensajeController::class, 'enviados'])->name('enviados');
        Route::get('/nuevo', [MensajeController::class, 'create'])->name('create');
        Route::post('/', [MensajeController::class, 'store'])->name('store');
        Route::get('/{mensaje}', [MensajeController::class, 'show'])->name('show');
        Route::delete('/{mensaje}/entrada', [MensajeController::class, 'destroyEntrada'])->name('destroy-entrada');
        Route::delete('/{mensaje}/enviados', [MensajeController::class, 'destroyEnviados'])->name('destroy-enviados');
    });

    // === Socias, protegida por autorización RoleMiddleware === 
    Route::middleware('role:Gerente,Entrenadora,Administrador')->group(function () {
        Route::get('/socias', [SociaController::class, 'index'])->name('socias.index');
        Route::post('/socias', [SociaController::class, 'store'])->name('socias.store');
        Route::get('/socias/create', [SociaController::class, 'create'])->name('socias.create');
        Route::get('/socias/{socia}', [SociaController::class, 'show'])->name('socias.show');
        Route::get('/socias/{socia}/edit', [SociaController::class, 'edit'])->name('socias.edit');
        Route::match(['put', 'patch'], '/socias/{socia}', [SociaController::class, 'update'])->name('socias.update');
        Route::patch('/socias/{socia}/estatus', [SociaController::class, 'toggleEstatus'])->name('socias.toggle-estatus');
        Route::delete('/socias/{socia}', [SociaController::class, 'destroy'])->name('socias.destroy');
    });

    // === Asistencia, protegida por autorización RoleMiddleware === 
    Route::middleware('role:Gerente,Entrenadora,Administrador')->group(function () {
        Route::get('/asistencia', [AsistenciaController::class, 'index'])->name('asistencia.index');
        Route::post('/asistencia', [AsistenciaController::class, 'store'])->name('asistencia.store');
        Route::delete('/asistencia', [AsistenciaController::class, 'destroy'])->name('asistencia.destroy');
        Route::post('/asistencia/verificar', [AsistenciaController::class, 'verificar'])->name('asistencia.verificar');
    });

    // === Reportes, protegida por autorización RoleMiddleware === 
    Route::middleware('role:Gerente,Entrenadora,Administrador')->group(function () {
        Route::get('/reportes/asistencia', [ReporteAsistenciaController::class, 'index'])->name('reportes.asistencia');
        Route::get('/reportes/asistencia/export', [ReporteAsistenciaController::class, 'export'])->name('reportes.asistencia.export');
    });

    // === Usuarios, protegida por autorización RoleMiddleware ===
    Route::middleware('role:Gerente,Entrenadora,Administrador')->group(function () {
        Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/create', [UserController::class, 'create'])->name('usuarios.create');
        Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');
        Route::get('/usuarios/{usuario}/edit', [UserController::class, 'edit'])->name('usuarios.edit');
        Route::match(['put', 'patch'], '/usuarios/{usuario}', [UserController::class, 'update'])->name('usuarios.update');
        Route::delete('/usuarios/{usuario}', [UserController::class, 'destroy'])->name('usuarios.destroy');
    });

    // === Ejercicios, protegida por autorización RoleMiddleware ===
    Route::middleware('role:Gerente,Entrenadora,Administrador')->group(function () {
        Route::get('/ejercicios', [EjercicioController::class, 'index'])->name('ejercicios.index');
        Route::post('/ejercicios', [EjercicioController::class, 'store'])->name('ejercicios.store');
        Route::get('/ejercicios/create', [EjercicioController::class, 'create'])->name('ejercicios.create');
        Route::get('/ejercicios/{ejercicio}/edit', [EjercicioController::class, 'edit'])->name('ejercicios.edit');
        Route::match(['put', 'patch'], '/ejercicios/{ejercicio}', [EjercicioController::class, 'update'])->name('ejercicios.update');
        Route::delete('/ejercicios/{ejercicio}', [EjercicioController::class, 'destroy'])->name('ejercicios.destroy');
    });

    // === Maquinas, protegida por autorización RoleMiddleware ===
    Route::middleware('role:Gerente,Entrenadora,Administrador')->group(function () {
        Route::get('/maquinas', [MaquinaController::class, 'index'])->name('maquinas.index');
        Route::post('/maquinas', [MaquinaController::class, 'store'])->name('maquinas.store');
        Route::get('/maquinas/create', [MaquinaController::class, 'create'])->name('maquinas.create');
        Route::get('/maquinas/{maquina}/edit', [MaquinaController::class, 'edit'])->name('maquinas.edit');
        Route::match(['put', 'patch'], '/maquinas/{maquina}', [MaquinaController::class, 'update'])->name('maquinas.update');
        Route::delete('/maquinas/{maquina}', [MaquinaController::class, 'destroy'])->name('maquinas.destroy');
    });

    // === Medidas, protegida por autorización RoleMiddleware ===
    Route::middleware('role:Gerente,Entrenadora,Administrador')->group(function () {
        Route::get('/medidas', [MedidaController::class, 'index'])->name('medidas.index');
        Route::post('/medidas', [MedidaController::class, 'store'])->name('medidas.store');
        Route::get('/medidas/create', [MedidaController::class, 'create'])->name('medidas.create');
        Route::get('/medidas/socias/{socia}/historial', [MedidaController::class, 'historial'])->name('medidas.historial');
        Route::get('/medidas/socias/{socia}/historial/export', [MedidaController::class, 'exportHistorial'])->name('medidas.historial.export');
        Route::get('/medidas/{medida}/edit', [MedidaController::class, 'edit'])->name('medidas.edit');
        Route::match(['put', 'patch'], '/medidas/{medida}', [MedidaController::class, 'update'])->name('medidas.update');
        Route::delete('/medidas/{medida}', [MedidaController::class, 'destroy'])->name('medidas.destroy');
    });

    // === Calendario, protegida por autorización RoleMiddleware ===
    Route::middleware('role:Gerente,Entrenadora,Administrador')->group(function () {
        Route::get('/calendario', [CalendarioController::class, 'index'])->name('calendario.index');
        Route::post('/calendario/asignar-maquina', [CalendarioController::class, 'asignarMaquina'])->name('calendario.asignar-maquina');
        Route::delete('/calendario/eliminar-maquina/{maquinaSemana}', [CalendarioController::class, 'eliminarMaquinaSemana'])->name('calendario.eliminar-maquina-semana');
        Route::post('/calendario/crear-evento', [CalendarioController::class, 'crearEvento'])->name('calendario.crear-evento');
        Route::delete('/calendario/eliminar-evento/{evento}', [CalendarioController::class, 'eliminarEvento'])->name('calendario.eliminar-evento');
    });

    // === Lanzamiento de Kiosko de asistencia, protegida por autorización KioskoMode ===
    Route::post('/kiosko/kiosko-iniciar', [KioskoController::class, 'kioskoIniciar'])
        ->middleware(['kiosko_mode'])
        ->name('kiosko.iniciar');

    // === Progreso, protegida por autorización RoleMiddleware ===
    Route::middleware('role:Socia')->group(function () {
        Route::get('/progreso/para-socia', [ProgresoSociaController::class, 'index'])->name('progreso.para-socia');
    });
});

require __DIR__.'/auth.php';
