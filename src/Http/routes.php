<?php
use Sitedigitalweb\Renault\Entrega;

Route::get('renault/app', function () {
   return View::make('renault::dashboard');
});

Route::get('renault/pdf', function () {
   return View::make('renault::pdf');
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
