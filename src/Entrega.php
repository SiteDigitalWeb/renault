<?php

namespace Sitedigitalweb\Renault;

use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
    protected $table = 'entregas';

    /**
     * Permite exportar / guardar todos los campos reales
     */
    protected $fillable = [
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

    /**
     * Cast automático a Carbon (fechas)
     */
    protected $casts = [
        'fecha_adjudicacion' => 'date',
        'fecha_facturacion'  => 'date',
        'factura_original'   => 'date',
        'envio_garantias'    => 'date',
        'recibido_garantias' => 'date',
        'radicacion_matricula' => 'date',
        'matricula_finalizada' => 'date',
        'solicitud_poliza'   => 'date',
        'radicada_poliza'    => 'date',
        'orden_entrega'      => 'date',
        'entrega_vehiculo'   => 'date',
    ];
}
