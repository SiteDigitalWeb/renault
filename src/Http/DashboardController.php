<?php

namespace Sitedigitalweb\Renault\Http;

use App\Http\Controllers\Controller;
use Sitedigitalweb\Renault\Entrega;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $entregas = Entrega::whereNotNull('fecha_adjudicacion')->get();

        /**
         * Promedio días adjudicación → factura
         */
        $avgAdjFactura = $entregas->filter(fn($e) =>
            $e->fecha_adjudicacion && $e->factura_original
        )->avg(fn($e) =>
            Carbon::parse($e->fecha_adjudicacion)
                ->diffInDays($e->factura_original)
        );

        /**
         * Promedio días factura → orden entrega
         */
        $avgFacturaOrden = $entregas->filter(fn($e) =>
            $e->factura_original && $e->orden_entrega
        )->avg(fn($e) =>
            Carbon::parse($e->factura_original)
                ->diffInDays($e->orden_entrega)
        );

        /**
         * Promedio total proceso
         * adjudicación → orden entrega
         */
        $avgTotalProceso = $entregas->filter(fn($e) =>
            $e->fecha_adjudicacion && $e->orden_entrega
        )->avg(fn($e) =>
            Carbon::parse($e->fecha_adjudicacion)
                ->diffInDays($e->orden_entrega)
        );

        /**
         * Promedio garantías
         */
        $avgGarantias = $entregas->filter(fn($e) =>
            $e->envio_garantias && $e->recibido_garantias
        )->avg(fn($e) =>
            Carbon::parse($e->envio_garantias)
                ->diffInDays($e->recibido_garantias)
        );

        /**
         * Promedio póliza
         */
        $avgPoliza = $entregas->filter(fn($e) =>
            $e->solicitud_poliza && $e->radicada_poliza
        )->avg(fn($e) =>
            Carbon::parse($e->solicitud_poliza)
                ->diffInDays($e->radicada_poliza)
        );

        return view('renault::dashboard', compact(
            'avgAdjFactura',
            'avgFacturaOrden',
            'avgTotalProceso',
            'avgGarantias',
            'avgPoliza'
        ));
    }
}
