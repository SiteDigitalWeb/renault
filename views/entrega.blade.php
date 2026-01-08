@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">📦 Importar / Exportar Entregas</h5>
                </div>

                <div class="card-body">

                    {{-- Mensajes --}}
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Importar --}}
                    <form action="{{ route('entregas.excel.importar') }}"
                          method="POST"
                          enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Archivo Excel</label>
                            <input type="file"
                                   name="archivo"
                                   class="form-control"
                                   required
                                   accept=".xlsx,.xls,.csv">
                        </div>

                        <button class="btn btn-primary w-100">
                            📥 Importar Excel
                        </button>
                    </form>

                    <hr>

                    {{-- Exportar --}}
                    <a href="{{ route('entregas.excel.exportar') }}"
                       class="btn btn-success w-100">
                        📤 Exportar Excel
                    </a>

                </div>
            </div>

        </div>
    </div>
</div>



@endsection
