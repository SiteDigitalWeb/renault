<?php

namespace Sitedigitalweb\Renault\Http;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Sitedigitalweb\Renault\Http\Imports\EntregasImport;
use Sitedigitalweb\Renault\Http\Exports\EntregasExport;

class EntregaExcelController
{
    public function index()
    {
        return view('renault::entrega');
    }

    public function importar(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new EntregasImport, $request->file('archivo'));

        return back()->with('success', 'Datos importados correctamente');
    }

    public function exportar()
    {
        return Excel::download(new EntregasExport, 'entregas.xlsx');
    }
}

