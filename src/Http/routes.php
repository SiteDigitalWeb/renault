<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HomeController;
use Sitedigitalweb\Renault\Entrega;
use Sitedigitalweb\Renault\Http\AuthController;
use Sitedigitalweb\Renault\Http\RenaultController;

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
$users = \Sitedigitalweb\Usuario\Tenant\Usuario::where('cedula', $id)->get();
return View::make('renault::pdf')->with('users',$users);
});

Route::get('renault/contrato-mandato/{id}', function ($id) {
$users = \Sitedigitalweb\Usuario\Tenant\Usuario::where('cedula', $id)->get();
return View::make('renault::mandato')->with('users',$users);
});

Route::get('renault/usuarios', function () {
$users = \Sitedigitalweb\Usuario\Tenant\Usuario::all();
return View::make('renault::users')->with('users',$users);
});


Route::group(['middleware' => ['web']], function (){
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