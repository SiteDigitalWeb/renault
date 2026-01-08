<?php

namespace Sitedigitalweb\Renault\Http\Imports;

use Sitedigitalweb\Renault\Entrega;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EntregasImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return Entrega::updateOrCreate(
            [
                // Clave única para actualizar o crear
                'orden_entrega' => $row['orden_entrega'] ?? null,
            ],
            [
                'fecha_adjudicacion'     => $row['fecha_adjudicacion'] ?? null,
                'fecha_facturacion'     => $row['fecha_facturacion'] ?? null,
                'factura_original'      => $row['factura_original'] ?? null,
                'envio_garantias'       => $row['envio_garantias'] ?? null,
                'solicitud_poliza'      => $row['solicitud_poliza'] ?? null,
                'radicada_poliza'       => $row['radicada_poliza'] ?? null,
                'radicacion_matricula'  => $row['radicacion_matricula'] ?? null,
                'matricula_finalizada'  => $row['matricula_finalizada'] ?? null,
                'recibido_garantias'    => $row['recibido_garantias'] ?? null,
                'orden_entrega'         => $row['orden_entrega'] ?? null,
                'entrega_vehiculo'      => $row['entrega_vehiculo'] ?? null,
                'observaciones'         => $row['observaciones'] ?? null,
                'user_id'               => $row['user_id'] ?? auth()->id(),
            ]
        );
    }
}