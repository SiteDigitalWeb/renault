@extends('adminsite.renault')

@section('cabecera')
 @parent
@stop

@section('ContenidoSite-01')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5><strong>Crear</strong> usuario</h5>
                </div>
                <div class="card-body">
                    {{ Form::open(array('method' => 'POST','class' => '','id' => 'defaultForm', 'url' => array('/renault/crear-usuario'))) }}

                    <div class="form-group">
                        <label class="form-label">Nombre:</label>
                        {{Form::text('name', '', array('class' => 'form-control','placeholder'=>'Ingrese nombre'))}}
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Primer Apellido:</label>
                        {{Form::text('last_name', '', array('class' => 'form-control','placeholder'=>'Ingrese apellido'))}}
                      
                    </div>
                    <div class="form-group">
                        <label class="form-label">Segundo Apellido:</label>
                        {{Form::text('last_name_second', '', array('class' => 'form-control','placeholder'=>'Ingrese apellido'))}}
                      
                    </div>

                    <div class="form-group">
                        <label class="form-label">Número Cédula:</label>
                        {{Form::text('cedula', '', array('class' => 'form-control','placeholder'=>'Ingrese número cédula'))}}
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Email:</label>
                        {{Form::text('email', '', array('class' => 'form-control','placeholder'=>'Ingrese email'))}}

                    </div>
                    
                    
                    <div class="form-group">
                        <label class="form-label">Dirección de residencia:</label>
                        {{Form::text('address', '', array('class' => 'form-control','placeholder'=>'Ingrese dirección'))}}
                      
                    </div>
              
                    
                    <div class="form-group">
                        <label class="form-label">Teléfono Fijo o Célular:</label>
                        {{Form::text('phone', '', array('class' => 'form-control','placeholder'=>'Ingrese teléfono fijo o célular'))}}
                    </div>
         
                    
                    <div class="form-group">
                        <label class="form-label">Contraseña:</label>
                        {{Form::password('password', array('class' => 'form-control','placeholder'=>'Registre password'))}}
                     
                    </div>
                    
                
                    <div class="form-group">
                        <label class="form-label">Confirmar Contraseña:</label>
                        {{Form::password('confirmPassword', array('class' => 'form-control','placeholder'=>'Confirme password'))}}
                     
                    </div>
                    
                     <div class="form-group">
                      <label for="inputTipoDoc">Tipo de Documento:</label>
                        {{ Form::select('tipo_documento', [
                         '' => '-- Seleccione un tipo --',
                         'C.C.' => 'Cédula de Ciudadanía (C.C.)',
                         'NIT' => 'Número de Identificación Tributaria (NIT)',
                         'N.N' => 'N.N (No Nombre)',
                         'PASAPORTE' => 'Pasaporte',
                         'C.EXTRANJERIA' => 'Cédula de Extranjería',
                         'T.IDENTIDAD' => 'Tarjeta de Identidad',
                         'NUIP' => 'Número Único de Identificación Personal (NUIP)',
                         'C.DIPLOMATICO' => 'Carné Diplomático'
                          ], null, [
                         'id' => 'inputTipoDoc',
                         'class' => 'form-control',
                         'required' => 'required',
                         'onchange' => 'actualizarMarcasDocumento()'
                        ]) }}
                </div>
                
                <div class="form-group">
                 <label for="inputTipoPersona">Tipo de Persona:</label>
                 {{ Form::select('tipo_persona', [
                  '' => '-- Seleccione una opción --',
                  'A NOMBRE PROPIO' => 'A NOMBRE PROPIO',
                  'REPRESENTANTE LEGAL' => 'REPRESENTANTE LEGAL'
                    ], null, [
                   'id' => 'inputTipoPersona',
                   'class' => 'form-control',
                   'required' => 'required',
                   'onchange' => 'actualizarMarcasSeleccion()'
                  ]) }}
                </div>



                    <div class="form-group">
                        <label class="form-label">Rol Usuario:</label>
                        {{ Form::select('level', ['' => '-- Seleccione rol --',
                        '1' => 'Administrador',
                        '2' => 'Comprador',
                        '3' => 'Fichador',
                        '4' => 'Recepcion',
                        '40' => 'Dresses_Admin'], null, array('class' => 'form-control')) }}
                    
                    </div>

                    <div class="form-group form-actions mt-4">
                        <button type="submit" class="btn btn-primary mr-2">
                            <i class="fa fa-angle-right"></i> Crear
                        </button>
                        <button type="reset" class="btn btn-warning">
                            <i class="fa fa-repeat"></i> Cancelar
                        </button>
                    </div>
                    
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
@parent

@stop
