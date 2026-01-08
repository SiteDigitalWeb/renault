<?php

namespace Sitedigitalweb\Renault\Http\Exports;

use Sitedigitalweb\Renault\Entrega;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EntregasExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Entrega::all();
    }

    /**
     * Orden y datos exactos que se exportan
     */
    public function map($entrega): array
    {
        return [
            $entrega->fecha_adjudicacion?->format('Y-m-d'),
            $entrega->fecha_facturacion?->format('Y-m-d'),
            $entrega->factura_original?->format('Y-m-d'),
            $entrega->envio_garantias?->format('Y-m-d'),
            $entrega->recibido_garantias?->format('Y-m-d'),
            $entrega->solicitud_poliza?->format('Y-m-d'),
            $entrega->radicada_poliza?->format('Y-m-d'),
            $entrega->radicacion_matricula?->format('Y-m-d'),
            $entrega->matricula_finalizada?->format('Y-m-d'),
            $entrega->orden_entrega?->format('Y-m-d'),
            $entrega->entrega_vehiculo?->format('Y-m-d'),
            $entrega->observaciones,
            $entrega->user_id,
        ];
    }

    /**
     * Encabezados del Excel
     */
    public function headings(): array
    {
        return [
            'fecha_adjudicacion',
            'fecha_facturacion',
            'factura_original',
            'envio_garantias',
            'solicitud_poliza',
            'radicada_poliza',
            'radicacion_matricula',
            'matricula_finalizada',
            'recibido_garantias',
            'orden_entrega',
            'entrega_vehiculo',
            'observaciones',
            'user_id',
        ];
    }
}







    


