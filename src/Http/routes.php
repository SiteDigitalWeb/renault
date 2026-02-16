<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use Sitedigitalweb\Renault\Entrega;
use Sitedigitalweb\Renault\Http\AuthController;
use Sitedigitalweb\Renault\Http\RenaultController;
use Sitedigitalweb\Renault\Http\DocumentController;
use Sitedigitalweb\Renault\Http\DocumentReviewController;




Route::get('renault/app', function () {
   return View::make('renault::dashboard');
});

Route::get('renault/home', function () {
   return View::make('renault::home');
});

Route::get('renault/crear-usuario', function () {
   return View::make('renault::crear-usuario');
});

Route::get('renault/pdf', function () {
   return View::make('renault::transito');
});


Route::get('renault/tramites', function () {
   return View::make('renault::tramites');
});


Route::post('renault/crear-usuario', 'Sitedigitalweb\Renault\Http\RenaultController@crear');


Route::get('renault/registro-transito/{id}', function ($id) {
    // Obtener el usuario por cédula
    $usuario = \Sitedigitalweb\Usuario\Tenant\Usuario::where('cedula', $id)->first();
    
    if (!$usuario) {
        abort(404, 'Usuario no encontrado');
    }
    
    // Obtener cantidad de suscriptores del campo
    $cantidadSuscriptores = $usuario->suscriptores ?? 1;
    
    // Si el campo está vacío o es 0, usar 1 por defecto
    if (empty($cantidadSuscriptores) || $cantidadSuscriptores < 1) {
        $cantidadSuscriptores = 1;
    }
    
    // Limitar a máximo 2 suscriptores (según tu requerimiento)
    if ($cantidadSuscriptores > 2) {
        $cantidadSuscriptores = 2;
    }
    
    // Crear array de suscriptores (replicando el mismo usuario)
    $suscriptores = [];
    for ($i = 0; $i < $cantidadSuscriptores; $i++) {
        $suscriptores[] = $usuario;
    }
    
    return View::make('renault::pdf')->with([
        'users' => [$usuario], // Para mantener compatibilidad con el bucle @foreach
        'user' => $usuario,    // Para acceder directamente al usuario
        'suscriptores' => $suscriptores,
        'cantidadSuscriptores' => $cantidadSuscriptores
    ]);
});

Route::get('renault/contrato-mandato/{id}', function ($id) {
$users = \Sitedigitalweb\Usuario\Tenant\Usuario::where('cedula', $id)->get();
return View::make('renault::mandato')->with('users',$users);
});

Route::get('renault/prenda/{id}', function ($id) {
$users = \Sitedigitalweb\Usuario\Tenant\Usuario::where('cedula', $id)->get();
return View::make('renault::prenda')->with('users',$users);
});

Route::get('renault/usuarios', function () {
$users = \Sitedigitalweb\Usuario\Tenant\Usuario::all();
return View::make('renault::users')->with('users',$users);
});


Route::group(['middleware' => ['web']], function (){
Route::view('renault/login','renault::auth.login');
Route::get('renault/entrega', function () {
   return View::make('renault::entrega');
});

});

Route::get('renault/seguimiento/{id}', function ($id) {
    $entrega = Entrega::findOrFail($id);
    return view('renault::seguimiento', compact('entrega'));
});

Route::get('renault/dashboard', [Sitedigitalweb\Renault\Http\DashboardController::class, 'index']);

Route::middleware(['web']) // 👈 ESTO ES LA CLAVE
    ->prefix('renault')
    ->name('entregas.excel.')
    ->group(function () {

        Route::get('/dako', [Sitedigitalweb\Renault\Http\EntregaExcelController::class, 'index'])
            ->name('index');

        Route::post('/importar', [Sitedigitalweb\Renault\Http\EntregaExcelController::class, 'importar'])
            ->name('importar');

        Route::get('/exportar', [Sitedigitalweb\Renault\Http\EntregaExcelController::class, 'exportar'])
            ->name('exportar');
    });




// Ruta de validación (con middleware web)
Route::middleware(['web'])->group(function () {
    Route::get('renault/auth', [AuthController::class, 'showValidationForm'])->name('home');
    
    // Validar cédula
    Route::post('/validate-cedula', [AuthController::class, 'validateCedula'])->name('validate.cedula');
    
    // Logout - FIJATE AQUÍ: usamos el namespace completo
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Ruta protegida para trámites (requiere validación)
Route::middleware(['web', 'validate.cedula'])->group(function () {
    Route::get('/renault/tramites', [RenaultController::class, 'showTramites'])->name('renault.tramites');
});



// En routes/web.php, agrega estas rutas para depurar:

Route::get('/debug/session', function() {
    dd(session()->all());
});

Route::get('/debug/users', function() {
    dd(\Sitedigitalweb\Renault\User::all());
});

Route::get('/debug/cedula/{cedula}', function($cedula) {
    $user = \Sitedigitalweb\Renault\User::where('cedula', $cedula)->first();
    dd($user);
});


Route::prefix('renault')
    ->name('renault.')
    ->middleware(['auth', 'ensure.docdir','renault'])
    ->group(function () {

       Route::resource('documents', DocumentController::class);
    
    // Ruta para descargar documento
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])
        ->name('documents.download');


    Route::post(
    'documents/{id}/complete-review',
    [DocumentReviewController::class, 'completeReview']
)->name('renault.review.complete');

   Route::post(
    'documents/{id}/review/comments',
    [DocumentReviewController::class, 'addComment']
)->name('review.add-comment');


Route::delete(
    'documents/{id}',
    [DocumentController::class, 'destroy']
)->name('documents.destroy');

    // Rutas de revisión
    Route::prefix('documents/{document}/review')->group(function () {
        Route::get('/', [DocumentReviewController::class, 'review'])->name('documents.review');
        Route::post('/commentss', [DocumentReviewController::class, 'addComment'])->name('review.add-comment');
        Route::get('/comments', [DocumentReviewController::class, 'getComments'])->name('review.comments');

        Route::patch(
    'renault/comments/{id}/status',
    [DocumentReviewController::class, 'updateCommentStatus']
)->name('review.update-comment-status');
Route::post('/complete', [DocumentReviewController::class, 'completeReview'])
            ->name('review.complete');
    });




    // Gestión de comentarios para el creador
    Route::get('/comments', [DocumentCommentController::class, 'index'])
        ->name('documents.comments');
    
    Route::post('/comments/bulk-resolve', [DocumentCommentController::class, 'bulkResolve'])
        ->name('comments.bulk-resolve');
    
    Route::get('/comments/export', [DocumentCommentController::class, 'export'])
        ->name('comments.export');


// API para respuestas
Route::post('/api/comments/reply', [DocumentCommentController::class, 'reply'])
    ->name('comments.reply')
    ->middleware('auth');

});
