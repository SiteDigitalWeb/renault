@extends('adminsite.renault')

@section('cabecera')
    @parent
@stop

@section('ContenidoSite-01')

@extends('adminsite.renault')

@section('ContenidoSite-01')

<div class="container">
    <h4 class="mb-4">📊 Dashboard de tiempos promedio</h4>

    <div class="row g-3">

        <div class="col-md-4">
            <div class="card border-primary">
                <div class="card-body">
                    <h6>Adjudicación → Factura</h6>
                    <h3>{{ round($avgAdjFactura) ?? 0 }} días</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body">
                    <h6>Factura → Orden entrega</h6>
                    <h3>{{ round($avgFacturaOrden) ?? 0 }} días</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-dark">
                <div class="card-body">
                    <h6>Total proceso</h6>
                    <h3>{{ round($avgTotalProceso) ?? 0 }} días</h3>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-warning">
                <div class="card-body">
                    <h6>Garantías</h6>
                    <h3>{{ round($avgGarantias) ?? 0 }} días</h3>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-info">
                <div class="card-body">
                    <h6>Póliza</h6>
                    <h3>{{ round($avgPoliza) ?? 0 }} días</h3>
                </div>
            </div>
        </div>

    </div>
</div>

@stop

<div class="row">
 <div class="col-md-6 col-xl-4">
   <hr>
  <div class="card">
   <h5 class="card-header">Notifcaciones</h5>
    <div class="card-body">
     <h5 class="card-title">Special title treatment</h5>
      <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
       <a href="#!" class="btn btn-primary">Ir a notificaciones</a>
    </div>
  </div>
 </div>

 <div class="col-md-6 col-xl-4">
   <hr>
  <div class="card">
   <h5 class="card-header">Trámites</h5>
    <div class="card-body">
     <h5 class="card-title">Special title treatment</h5>
      <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
       <a href="#!" class="btn btn-primary">Ir a trámites</a>
    </div>
  </div>
 </div>

 <div class="col-md-6 col-xl-4">
   <hr>
  <div class="card">
   <h5 class="card-header">Seguros</h5>
    <div class="card-body">
     <h5 class="card-title">Special title treatment</h5>
      <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
       <a href="#!" class="btn btn-primary">Ir a seguros</a>
    </div>
  </div>
 </div>

 <div class="col-md-6 col-xl-4">
   <hr>
  <div class="card">
   <h5 class="card-header">Documentos</h5>
    <div class="card-body">
     <h5 class="card-title">Special title treatment</h5>
      <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
       <a href="#!" class="btn btn-primary">Ir a documentos</a>
    </div>
  </div>
 </div>
</div>




@stop

@section('scripts')
@parent

@stop