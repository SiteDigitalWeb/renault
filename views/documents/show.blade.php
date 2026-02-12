@extends('layouts.app')

@section('title', 'Detalles del Documento')

@section('content')
{{-- En la sección de acciones --}}
@if(auth()->user()->id == $document->uploaded_by || auth()->user()->is_admin)
<a href="{{ route('renault.documents.comments', $document) }}" class="btn btn-info w-100 mb-2">
    <i class="fas fa-comments"></i> Ver Todos los Comentarios
</a>
@endif
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">{{ $document->title }}</h4>
                        <div>
                            <a href="{{ route('renault.documents.download', $document) }}" 
                               class="btn btn-success btn-sm">
                                <i class="fas fa-download"></i> Descargar
                            </a>
                            <a href="{{ route('renault.documents.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6>Información del Documento</h6>
                            <table class="table table-sm">
                                <tr>
                                    <th>Nombre original:</th>
                                    <td>{{ $document->original_name }}</td>
                                </tr>
                                <tr>
                                    <th>Tamaño:</th>
                                    <td>{{ $document->file_size }}</td>
                                </tr>
                                <tr>
                                    <th>Subido por:</th>
                                    <td>{{ $document->uploader->name }}</td>
                                </tr>
                                <tr>
                                    <th>Fecha de subida:</th>
                                    <td>{{ $document->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Estado:</th>
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
                                </tr>
                                <tr>
                                    <th>Ubicación:</th>
                                    <td><code>{{ $document->file_path }}</code></td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h6>Vista Previa</h6>
                            <div class="border rounded p-3 text-center">
                                <i class="fas fa-file-pdf fa-4x text-danger mb-2"></i>
                                <p class="mb-0">{{ $document->original_name }}</p>
                                <small class="text-muted">{{ $document->file_size }}</small>
                                <div class="mt-2">
                                    <a href="/{{ $document->file_path }}" 
                                       target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-external-link-alt"></i> Ver en nueva pestaña
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h5>Revisores Asignados</h5>
                    <div class="row">
                        @foreach($reviewers as $reviewer)
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $reviewer->user->name }}</h6>
                                            <small class="text-muted">{{ $reviewer->user->email }}</small>
                                        </div>
                                        <span class="badge bg-{{ 
                                            $reviewer->status == 'pendiente' ? 'secondary' : 
                                            ($reviewer->status == 'en_progreso' ? 'warning' : 'success') 
                                        }}">
                                            {{ ucfirst($reviewer->status) }}
                                        </span>
                                    </div>
                                    
                                    @if($reviewer->comentario_general)
                                    <div class="mt-2 p-2 bg-light rounded">
                                        <small><strong>Comentario General:</strong></small>
                                        <p class="mb-0 small">{{ $reviewer->comentario_general }}</p>
                                    </div>
                                    @endif
                                    
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            Asignado: {{ $reviewer->created_at->format('d/m/Y') }}
                                            @if($reviewer->completed_at)
                                            <br>Completado: {{ $reviewer->completed_at->format('d/m/Y') }}
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <hr>
                    
                    <h5>Comentarios ({{ $document->comments->count() }})</h5>
                    @if($document->comments->count() > 0)
                    <div class="accordion" id="commentsAccordion">
                        @foreach($document->comments->groupBy('page_number') as $page => $pageComments)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{ $page }}">
                                <button class="accordion-button collapsed" type="button" 
                                        data-bs-toggle="collapse" data-bs-target="#collapse{{ $page }}">
                                    Página {{ $page }} ({{ $pageComments->count() }} comentarios)
                                </button>
                            </h2>
                            <div id="collapse{{ $page }}" class="accordion-collapse collapse" 
                                 data-bs-parent="#commentsAccordion">
                                <div class="accordion-body">
                                    @foreach($pageComments as $comment)
                                    <div class="card mb-2 border-{{ 
                                        $comment->type == 'comment' ? 'info' : 
                                        ($comment->type == 'suggestion' ? 'success' : 'danger') 
                                    }}">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1">{{ $comment->user->name }}</h6>
                                                    <small class="text-muted">
                                                        {{ $comment->created_at->format('d/m/Y H:i') }} • 
                                                        {{ $comment->type_text }}
                                                    </small>
                                                </div>
                                                <span class="badge bg-{{ 
                                                    $comment->status == 'open' ? 'warning' : 
                                                    ($comment->status == 'resolved' ? 'success' : 'secondary') 
                                                }}">
                                                    {{ $comment->status_text }}
                                                </span>
                                            </div>
                                            <p class="mb-0 mt-2">{{ $comment->content }}</p>
                                            @if($comment->resolved_by && $comment->resolved_at)
                                            <small class="text-muted">
                                                Resuelto por {{ $comment->resolver->name ?? 'Usuario' }} 
                                                el {{ $comment->resolved_at->format('d/m/Y H:i') }}
                                            </small>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No hay comentarios aún.
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Acciones</h5>
                </div>
                <div class="card-body">
                    @if($document->isAssignedTo(auth()->id()) && 
                        $document->getReviewStatusForUser(auth()->id()) != 'completado')
                    <a href="{{ route('renault.documents.review', $document) }}" 
                       class="btn btn-warning w-100 mb-2">
                        <i class="fas fa-edit"></i> Revisar Documento
                    </a>
                    @endif
                    
                    @if(auth()->user()->id == $document->uploaded_by || auth()->user()->hasRole('admin'))
                    <button type="button" class="btn btn-danger w-100" 
                            onclick="confirmDelete({{ $document->id }})">
                        <i class="fas fa-trash"></i> Eliminar Documento
                    </button>
                    
                    <form id="delete-form-{{ $document->id }}" 
                          action="{{ route('renault.documents.destroy', $document) }}" 
                          method="POST" class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                    @endif
                    
                    <hr>
                    
                    <div class="mt-3">
                        <h6>Estadísticas</h6>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Total comentarios:</span>
                                <strong>{{ $document->comments->count() }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Comentarios abiertos:</span>
                                <strong>{{ $document->comments->where('status', 'open')->count() }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Comentarios resueltos:</span>
                                <strong>{{ $document->comments->where('status', 'resolved')->count() }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Revisores completados:</span>
                                <strong>{{ $document->reviewers->where('status', 'completado')->count() }}/{{ $document->reviewers->count() }}</strong>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            @if($document->comments->count() > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Resumen de Comentarios</h5>
                </div>
                <div class="card-body">
                    <canvas id="commentsChart" height="200"></canvas>
                </div>
            </div>
            
            @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const ctx = document.getElementById('commentsChart').getContext('2d');
                    const comments = @json($document->comments);
                    
                    const types = {
                        'comment': comments.filter(c => c.type === 'comment').length,
                        'suggestion': comments.filter(c => c.type === 'suggestion').length,
                        'correction': comments.filter(c => c.type === 'correction').length
                    };
                    
                    new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Comentarios', 'Sugerencias', 'Correcciones'],
                            datasets: [{
                                data: [types.comment, types.suggestion, types.correction],
                                backgroundColor: [
                                    '#0dcaf0',
                                    '#198754',
                                    '#dc3545'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            const label = context.label || '';
                                            const value = context.raw || 0;
                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            const percentage = Math.round((value / total) * 100);
                                            return `${label}: ${value} (${percentage}%)`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                });
                
                function confirmDelete(documentId) {
                    if (confirm('¿Estás seguro de eliminar este documento? Esta acción no se puede deshacer.')) {
                        document.getElementById('delete-form-' + documentId).submit();
                    }
                }
            </script>
            @endpush
            @endif
        </div>
    </div>
</div>
@endsection