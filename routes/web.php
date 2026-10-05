<?php

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\DocenteDashboardController;
use App\Http\Controllers\PadreDashboardController;
use App\Http\Controllers\Docente\IncidenciaController;
use App\Http\Controllers\Docente\CursoController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\CursoController as AdminCursoController;
use App\Http\Controllers\Admin\EstudianteController as AdminEstudianteController;
use App\Http\Controllers\Docente\EstudianteController;
use App\Http\Controllers\Docente\ReporteController;
use App\Http\Controllers\Docente\CursoSelectorController;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Docente\EstadisticasController;
use App\Http\Controllers\Docente\PerfilController;
use App\Http\Controllers\PadreController;
use App\Http\Controllers\Docente\CitacionController;
use App\Http\Controllers\Admin\ConfiguracionController;
use App\Http\Controllers\Admin\CitacionController as AdminCitacionController;
use App\Http\Controllers\FotoEstudianteController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;


Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
        ->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
        ->name('password.update');
});

// ===================== FOTOS =====================
Route::get('/fotos-estudiantes', [FotoEstudianteController::class, 'index']);
Route::post('/fotos-estudiantes/{estudiante}', [FotoEstudianteController::class, 'upload'])->name('fotos-estudiantes.upload');

// ===================== PÁGINA PRINCIPAL =====================
Route::get('/', function () {
    return redirect('/login');
});

// ===================== REGISTRO =====================
Route::get('/register', function () {
    return view('auth.register-info');
})->name('register');

// ===================== LOGIN / LOGOUT =====================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ===================== DASHBOARDS (protegidos por autenticación) =====================
Route::middleware('auth')->group(function () {

    // --- Dashboards por rol ---
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/docente/dashboard', [DocenteDashboardController::class, 'index'])->name('docente.dashboard');
    Route::get('/padre/dashboard', [PadreDashboardController::class, 'index'])->name('padre.dashboard');

    // ============================================
    // MÓDULO DOCENTE: INCIDENCIAS
    // ============================================
    // Crear y almacenar incidencias
    Route::get('/docente/incidencia/crear', [IncidenciaController::class, 'create'])->name('docente.incidencia.create');
    Route::post('/docente/incidencia', [IncidenciaController::class, 'store'])->name('docente.incidencia.store');
    Route::post('/docente/incidencia/masiva', [IncidenciaController::class, 'storeMasiva'])->name('docente.incidencia.store.masiva');
    
    // AJAX para incidencias
    Route::get('/docente/incidencias/estudiante/{id}', [IncidenciaController::class, 'getIncidenciasEstudiante'])->name('docente.incidencias.estudiante');
    Route::get('/docente/incidencia/{id}/detalle', [IncidenciaController::class, 'getDetalleIncidencia'])->name('docente.incidencia.detalle');
    Route::get('/docente/incidencias/estudiante/{id}/listado', [IncidenciaController::class, 'listadoIncidencias'])->name('docente.incidencias.listado');
    
    // WhatsApp para incidencias
    Route::get('/docente/incidencia/enviar-whatsapp/{id}', [IncidenciaController::class, 'enviarWhatsAppIncidencia'])->name('docente.incidencia.enviar.whatsapp');
    Route::post('/docente/incidencia/enviar-whatsapp-masivo', [IncidenciaController::class, 'enviarWhatsAppMasivo'])->name('docente.incidencia.enviar.whatsapp.masivo');

    // Citaciones desde incidencias
    Route::get('/docente/incidencia/citacion/{id}', [IncidenciaController::class, 'mostrarCitacion'])->name('docente.incidencia.citacion');
    Route::get('/docente/incidencia/enviar-whatsapp/{id}', [IncidenciaController::class, 'enviarCitacionWhatsApp'])->name('docente.incidencia.enviar.whatsapp');

    // ============================================
    // MÓDULO DOCENTE: ESTUDIANTES Y CURSOS
    // ============================================
    Route::get('/docente/curso/{curso}/estudiantes', [CursoController::class, 'estudiantes'])->name('docente.curso.estudiantes');
    Route::get('/docente/estudiante/{id}/kardex', [EstudianteController::class, 'kardex'])->name('docente.estudiante.kardex');

    // ============================================
    // MÓDULO DOCENTE: REPORTES
    // ============================================
    Route::get('/docente/reporte/pdf/{estudiante}', [ReporteController::class, 'generarPDF'])->name('docente.reporte.pdf');
    Route::get('/docente/reporte/whatsapp/{estudiante}', [ReporteController::class, 'whatsapp'])->name('docente.reporte.whatsapp');

    // ============================================
    // MÓDULO DOCENTE: SELECCIÓN DE CURSOS
    // ============================================
    Route::get('/docente/cursos', [CursoSelectorController::class, 'index'])->name('docente.cursos.seleccionar');
    Route::get('/docente/cursos/seleccionar/{id}', [CursoSelectorController::class, 'seleccionar'])->name('docente.cursos.seleccionar.curso');

    // ============================================
    // MÓDULO DOCENTE: ESTADÍSTICAS
    // ============================================
    Route::get('/docente/estadisticas', [EstadisticasController::class, 'index'])->name('docente.estadisticas.index');
    Route::get('/docente/estadisticas/estudiante/{id}', [EstadisticasController::class, 'estudiante'])->name('docente.estadisticas.estudiante');
    Route::get('/docente/estadisticas/incidencias/{id}', [EstadisticasController::class, 'getIncidenciasEstudiante'])->name('docente.estadisticas.incidencias');

    // ============================================
    // MÓDULO DOCENTE: PERFIL
    // ============================================
    Route::get('/docente/perfil', [PerfilController::class, 'index'])->name('docente.perfil');
    Route::put('/docente/perfil', [PerfilController::class, 'update'])->name('docente.perfil.update');

    // ============================================
    // MÓDULO DOCENTE: CITACIONES
    // ============================================
    Route::get('/docente/citacion/crear/{estudiante}', [CitacionController::class, 'create'])->name('docente.citacion.create');
    Route::post('/docente/citacion', [CitacionController::class, 'store'])->name('docente.citacion.store');
    Route::get('/docente/citaciones', [CitacionController::class, 'index'])->name('docente.citaciones.index');
    Route::get('/docente/citacion/{id}', [CitacionController::class, 'show'])->name('docente.citacion.show');
    Route::put('/docente/citacion/{id}', [CitacionController::class, 'update'])->name('docente.citacion.update');
    Route::delete('/docente/citacion/{id}', [CitacionController::class, 'destroy'])->name('docente.citacion.destroy');
});

// ===================== ADMINISTRADOR =====================
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Gestión de usuarios
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('admin.usuarios.index');
    Route::get('/usuarios/crear', [UsuarioController::class, 'create'])->name('admin.usuarios.create');
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('admin.usuarios.store');
    Route::get('/usuarios/{id}/editar', [UsuarioController::class, 'edit'])->name('admin.usuarios.edit');
    Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('admin.usuarios.update');
    Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('admin.usuarios.destroy');

    // Gestión de cursos
    Route::get('/cursos', [AdminCursoController::class, 'index'])->name('admin.cursos.index');
    Route::get('/cursos/crear', [AdminCursoController::class, 'create'])->name('admin.cursos.create');
    Route::post('/cursos', [AdminCursoController::class, 'store'])->name('admin.cursos.store');
    Route::get('/cursos/{id}/editar', [AdminCursoController::class, 'edit'])->name('admin.cursos.edit');
    Route::put('/cursos/{id}', [AdminCursoController::class, 'update'])->name('admin.cursos.update');
    Route::delete('/cursos/{id}', [AdminCursoController::class, 'destroy'])->name('admin.cursos.destroy');

    // Ver estudiantes por curso
    Route::get('/cursos/{id}/estudiantes', [AdminCursoController::class, 'estudiantes'])->name('admin.cursos.estudiantes');
    Route::get('/estudiantes/{id}/kardex', [AdminEstudianteController::class, 'kardex'])->name('admin.estudiantes.kardex');

    // Citaciones admin
    Route::get('/citaciones', [AdminCitacionController::class, 'index'])->name('admin.citaciones.index');
    Route::get('/citaciones/create', [AdminCitacionController::class, 'create'])->name('admin.citaciones.create');
    Route::post('/citaciones', [AdminCitacionController::class, 'store'])->name('admin.citaciones.store');
    Route::get('/citaciones/{id}', [AdminCitacionController::class, 'show'])->name('admin.citaciones.show');
    Route::get('/citaciones/{id}/edit', [AdminCitacionController::class, 'edit'])->name('admin.citaciones.edit');
    Route::put('/citaciones/{id}', [AdminCitacionController::class, 'update'])->name('admin.citaciones.update');
    Route::delete('/citaciones/{id}', [AdminCitacionController::class, 'destroy'])->name('admin.citaciones.destroy');
    Route::post('/citaciones/{id}/enviar', [AdminCitacionController::class, 'enviar'])->name('admin.citaciones.enviar');
    Route::get('/citaciones/historial', [AdminCitacionController::class, 'historial'])->name('admin.citaciones.historial');
    
    // ⭐ RUTA CORREGIDA - USANDO AdminCitacionController ⭐
    Route::get('/citaciones/{id}/enviar-whatsapp', [AdminCitacionController::class, 'enviarWhatsApp'])->name('admin.citaciones.enviar.whatsapp');

    // Configuración
    Route::get('/configuracion', [ConfiguracionController::class, 'index'])->name('admin.configuracion.index');
    Route::put('/configuracion', [ConfiguracionController::class, 'update'])->name('admin.configuracion.update');
});

// ===================== PADRE DE FAMILIA =====================
Route::middleware('auth')->group(function () {
    Route::get('/padre/dashboard', [PadreController::class, 'dashboard'])->name('padre.dashboard');
    Route::get('/padre/kardex/{id}', [PadreController::class, 'kardex'])->name('padre.kardex');
    Route::post('/padre/citacion/confirmar/{id}', [PadreController::class, 'confirmarCitacion'])->name('padre.citacion.confirmar');
    Route::get('/padre/historial/citaciones', [PadreController::class, 'historialCitaciones'])->name('padre.historial.citaciones');
    Route::get('/padre/perfil', [PadreController::class, 'perfil'])->name('padre.perfil');
    Route::put('/padre/perfil', [PadreController::class, 'updatePerfil'])->name('padre.perfil.update');
    Route::get('/padre/kardex/pdf/{id}', [PadreController::class, 'descargarPDF'])->name('padre.kardex.pdf');
    
    // ⭐⭐ ESTA RUTA DEBE ESTAR ANTES DE test-padre-asistencia ⭐⭐
    Route::get('/padre/asistencia', [PadreController::class, 'asistencia'])->name('padre.asistencia');
    
    Route::get('/test-padre-asistencia', function () {
        return "Ruta de prueba funciona!";
    });
});


Route::middleware(['auth', 'regente'])->prefix('regente')->name('regente.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\RegenteController::class, 'dashboard'])->name('dashboard');
    Route::get('/asistencia', [App\Http\Controllers\RegenteController::class, 'asistencia'])->name('asistencia');
    Route::post('/asistencia/registrar', [App\Http\Controllers\RegenteController::class, 'registrarAsistencia'])->name('asistencia.registrar');
    Route::get('/asistencia/estudiante/{id}', [App\Http\Controllers\RegenteController::class, 'asistenciaEstudiante'])->name('asistencia.estudiante');
});



// ===================== PRUEBA DE WHATSAPP =====================
Route::get('/test-whatsapp-meta', function () {
    $telefono = '59169640294';
    
    $mensaje = "📢 *PRUEBA DESDE LARAVEL (META)*\n\n";
    $mensaje .= "Este es un mensaje de prueba para verificar la conexión con Meta WhatsApp.";
    
    try {
        $url = "https://graph.facebook.com/" . env('META_WHATSAPP_API_VERSION') . "/" . env('META_WHATSAPP_PHONE_ID') . "/messages";
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('META_WHATSAPP_TOKEN'),
            'Content-Type' => 'application/json',
        ])->post($url, [
            'messaging_product' => 'whatsapp',
            'to' => $telefono,
            'type' => 'text',
            'text' => [
                'body' => $mensaje
            ]
        ]);
        
        if ($response->successful()) {
            return "✅ Mensaje enviado correctamente a {$telefono}<br><br>" . $response->body();
        } else {
            return "❌ Error: " . $response->body();
        }
        
    } catch (\Exception $e) {
        return "❌ Error: " . $e->getMessage();
    }
});