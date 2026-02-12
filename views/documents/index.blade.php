@extends('layouts.app')

@section('title', 'Mis Documentos')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4">Documentos</h1>
    
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-0">Lista de Documentos</h5>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('renault.documents.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nuevo Documento
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            @if($documents->isEmpty())
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> No hay documentos disponibles.
            </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Subido por</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Revisores</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($documents as $document)
                        <tr>
                            <td>
                                <strong>{{ $document->title }}</strong><br>
                                <small class="text-muted">{{ $document->original_name }}</small>
                            </td>
                            <td>{{ $document->uploader->name }}</td>
                            <td>{{ $document->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @php
                                    $statusColors = [
                                        'pendiente' => 'secondary',
                                        'en_revision' => 'warning',
                                        'revisado' => 'info',
                                        'aprobado' => 'success',
                                        'rechazado' => 'danger'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$document->status] }}">
                                    {{ ucfirst(str_replace('_', ' ', $document->status)) }}
                                </span>
                            </td>
                            <td>
                                @foreach($document->reviewers as $reviewer)
                                    @php
                                        $reviewerColors = [
                                            'pendiente' => 'secondary',
                                            'en_progreso' => 'warning',
                                            'completado' => 'success'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $reviewerColors[$reviewer->status] }} mb-1" 
                                          title="{{ $reviewer->user->name }} - {{ $reviewer->status }}">
                                        {{ substr($reviewer->user->name, 0, 1) }}
                                    </span>
                                @endforeach
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('renault.documents.show', $document) }}" 
                                       class="btn btn-sm btn-info" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <a href="{{ route('renault.documents.download', $document) }}" 
                                       class="btn btn-sm btn-success" title="Descargar">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    
                                    @if($document->isAssignedTo(auth()->id()) && 
                                        $document->getReviewStatusForUser(auth()->id()) != 'completado')
                                    <a href="{{ route('documents.review', $document) }}" 
                                       class="btn btn-sm btn-warning" title="Revisar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endif
                                    
                                    @if(auth()->user()->id == $document->uploaded_by || auth()->user()->hasRole('admin'))
                                    <button type="button" class="btn btn-sm btn-danger" 
                                            onclick="confirmDelete({{ $document->id }})" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                </div>
                                
                                <form id="delete-form-{{ $document->id }}" 
                                      action="{{ route('documents.destroy', $document) }}" 
                                      method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{ $documents->links() }}
            @endif
        </div>
    </div>
</div>

<script>
function confirmDelete(documentId) {
    if (confirm('¿Estás seguro de eliminar este documento?')) {
        document.getElementById('delete-form-' + documentId).submit();
    }
}
</script>
@endsection