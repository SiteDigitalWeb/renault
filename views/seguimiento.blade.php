@extends('adminsite.renault')

@section('ContenidoSite-01')

@php
use Carbon\Carbon;

/**
 * Calcula días entre fechas
 */
function dias($inicio, $fin) {
    if (!$inicio || !$fin) return null;
    return Carbon::parse($inicio)->diffInDays(Carbon::parse($fin));
}

/**
 * Determina estado del step
 * completed = verde
 * pending   = naranja
 */
function estado($inicio, $fin) {
    if ($fin) return 'completed';
    if ($inicio) return 'pending';
    return '';
}
@endphp

@php


/**
 * Total de días del proceso
 * fecha_adjudicacion → orden_entrega
 */
$totalDiasProceso = null;
if ($entrega->fecha_adjudicacion && $entrega->orden_entrega) {
    $totalDiasProceso = Carbon::parse($entrega->fecha_adjudicacion)
        ->diffInDays(Carbon::parse($entrega->orden_entrega));
}

/**
 * Días desde factura original → orden de entrega
 */
$diasFacturaOrden = null;
if ($entrega->factura_original && $entrega->orden_entrega) {
    $diasFacturaOrden = Carbon::parse($entrega->factura_original)
        ->diffInDays(Carbon::parse($entrega->orden_entrega));
}

$diasAdjudicacionFactura = null;

if ($entrega->fecha_adjudicacion && $entrega->factura_original) {
    $diasAdjudicacionFactura = Carbon::parse($entrega->fecha_adjudicacion)
        ->diffInDays(Carbon::parse($entrega->factura_original));
}
@endphp


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Seguimiento del proceso</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .step-number {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .step.pending .step-number {
            background: #fd7e14; /* naranja */
            color: #fff;
        }

        .step.completed .step-number {
            background: #198754; /* verde */
            color: #fff;
        }
    </style>
</head>

<body class="p-3">

<div class="container">
    <h5 class="mb-4">Estado del proceso</h5>

    {{-- PASO 1 - OPERACIONES --}}
    <div class="card mb-3 step {{ estado($entrega->fecha_adjudicacion, $entrega->fecha_facturacion) }}">
        <div class="card-header d-flex gap-2 align-items-center">
            <div class="step-number">1</div>
            <strong>Operaciones</strong>
        </div>
        <div class="card-body">
            <div>Fecha adjudicación: <strong>{{ $entrega->fecha_adjudicacion ?? 'Pendiente' }}</strong></div>
            <div>Fecha facturación: <strong>{{ $entrega->fecha_facturacion ?? 'Pendiente' }}</strong></div>

            @if(dias($entrega->fecha_adjudicacion, $entrega->fecha_facturacion))
                <div class="mt-2 text-muted">
                    ⏱ {{ dias($entrega->fecha_adjudicacion, $entrega->fecha_facturacion) }} días
                </div>
            @endif
        </div>
    </div>

    {{-- PASO 2 - GARANTÍAS --}}
    <div class="card mb-3 step {{ estado($entrega->envio_garantias, $entrega->recibido_garantias) }}">
        <div class="card-header d-flex gap-2 align-items-center">
            <div class="step-number">2</div>
            <strong>Garantías</strong>
        </div>
        <div class="card-body">
            <div>Envío garantías: <strong>{{ $entrega->envio_garantias ?? 'Pendiente' }}</strong></div>
            <div>Recibido garantías: <strong>{{ $entrega->recibido_garantias ?? 'Pendiente' }}</strong></div>

            @if(dias($entrega->envio_garantias, $entrega->recibido_garantias))
                <div class="mt-2 text-muted">
                    ⏱ {{ dias($entrega->envio_garantias, $entrega->recibido_garantias) }} días
                </div>
            @endif
        </div>
    </div>

    {{-- PASO 3 - MATRÍCULA --}}
    <div class="card mb-3 step {{ estado($entrega->radicacion_matricula, $entrega->matricula_finalizada) }}">
        <div class="card-header d-flex gap-2 align-items-center">
            <div class="step-number">3</div>
            <strong>Matrícula</strong>
        </div>
        <div class="card-body">
            <div>Factura original: <strong>{{ $entrega->radicacion_matricula ?? 'Pendiente' }}</strong></div>
            <div>Matrícula: <strong>{{ $entrega->matricula_finalizada ?? 'Pendiente' }}</strong></div>

            @if(dias($entrega->radicacion_matricula, $entrega->matricula_finalizada))
                <div class="mt-2 text-muted">
                    ⏱ {{ dias($entrega->radicacion_matricula, $entrega->matricula_finalizada) }} días
                </div>
            @endif
        </div>
    </div>

    {{-- PASO 4 - PÓLIZA --}}
    <div class="card mb-3 step {{ estado($entrega->solicitud_poliza, $entrega->radicada_poliza) }}">
        <div class="card-header d-flex gap-2 align-items-center">
            <div class="step-number">4</div>
            <strong>Póliza</strong>
        </div>
        <div class="card-body">
            <div>Solicitud póliza: <strong>{{ $entrega->solicitud_poliza ?? 'Pendiente' }}</strong></div>
            <div>Póliza radicada: <strong>{{ $entrega->radicada_poliza ?? 'Pendiente' }}</strong></div>

            @if(dias($entrega->solicitud_poliza, $entrega->radicada_poliza))
                <div class="mt-2 text-muted">
                    ⏱ {{ dias($entrega->solicitud_poliza, $entrega->radicada_poliza) }} días
                </div>
            @endif
        </div>
    </div>

    {{-- PASO 5 - ENTREGA --}}
    <div class="card mb-3 step {{ estado($entrega->orden_entrega, $entrega->entrega_vehiculo) }}">
        <div class="card-header d-flex gap-2 align-items-center">
            <div class="step-number">5</div>
            <strong>Entrega del vehículo</strong>
        </div>
        <div class="card-body">
            <div>Orden de entrega: <strong>{{ $entrega->orden_entrega ?? 'Pendiente' }}</strong></div>
            <div>Vehículo entregado: <strong>{{ $entrega->entrega_vehiculo ?? 'Pendiente' }}</strong></div>

            @if(dias($entrega->orden_entrega, $entrega->entrega_vehiculo))
                <div class="mt-2 text-muted">
                    ⏱ {{ dias($entrega->orden_entrega, $entrega->entrega_vehiculo) }} días
                </div>
            @endif
        </div>
    </div>

</div>

<div class="card mb-4 border-primary">
    <div class="card-body">
        <h6 class="mb-3">Resumen del proceso</h6>

        <div>
            🕒 <strong>Total de días del proceso:</strong>
            @if($totalDiasProceso !== null)
                {{ $totalDiasProceso }} días
            @else
                Pendiente
            @endif
        </div>

        <div class="mt-2">
            📄➡️🚚 <strong>Días desde factura original hasta orden de entrega:</strong>
            @if($diasFacturaOrden !== null)
                {{ $diasFacturaOrden }} días
            @else
                Pendiente
            @endif
        </div>


       	<div class="mt-2">
    📄 <strong>Días entre adjudicación y factura:</strong>
    @if($diasAdjudicacionFactura !== null)
        {{ $diasAdjudicacionFactura }} días
    @else
        Pendiente
    @endif
</div>

    </div>
</div>


</body>
</html>

@stop

