@extends('layouts.app')

@section('title', 'Gestión de Comentarios - ' . $document->title)

@section('styles')
<style>
    .comment-filters {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }
    .comment-card {
        border-left: 4px solid;
        transition: all 0.3s;
        margin-bottom: 15px;
    }
    .comment-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .comment-status-open { border-left-color: #ffc107; }
    .comment-status-resolved { border-left-color: #198754; }
    .comment-status-closed { border-left-color: #6c757d; }
    .comment-type-comment { background-color: #e3f2fd; }
    .comment-type-suggestion { background-color: #e8f5e8; }
    .comment-type-correction { background-color: #fde8e8; }
    .comment-actions {
        opacity: 0;
        transition: opacity 0.3s;
    }
    .comment-card:hover .comment-actions {
        opacity: 1;
    }
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .page-link {
        cursor: pointer;
    }
    .page-link.active {
        background-color: #0d6efd;
        color: white;
        border-color: #0d6efd;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">Gestión de Comentarios</h1>
                    <p class="text-muted mb-0">{{ $document->title }}</p>
                    <small>Subido por: {{ $document->uploader->name }} | 
                    Estado: <span class="badge bg-{{ 
                        $document->status == 'pendiente' ? 'secondary' : 
                        ($document->status == 'en_revision' ? 'warning' : 
                        ($document->status == 'revisado' ? 'info' : 
                        ($document->status == 'aprobado' ? 'success' : 'danger'))) 
                    }}">{{ ucfirst(str_replace('_', ' ', $document->status)) }}</span></small>
                </div>
                <div>
                    <a href="{{ route('documents.show', $document) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver al Documento
                    </a>
                    <a href="{{ route('documents.review', $document) }}" class="btn btn-primary">
                        <i class="fas fa-eye"></i> Ver en Revisor
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <h5><i class="fas fa-comments"></i> Total Comentarios</h5>
                <h2 class="mb-0">{{ $comments->count() }}</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <h5><i class="fas fa-exclamation-circle"></i> Abiertos</h5>
                <h2 class="mb-0">{{ $comments->where('status', 'open')->count() }}</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <h5><i class="fas fa-check-circle"></i> Resueltos</h5>
                <h2 class="mb-0">{{ $comments->where('status', 'resolved')->count() }}</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <h5><i class="fas fa-chart-pie"></i> Por Página</h5>
                <h2 class="mb-0">{{ $comments->groupBy('page_number')->count() }}</h2>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="comment-filters">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Filtrar por Estado</label>
                        <select class="form-select" id="filter-status">
                            <option value="all">Todos los estados</option>
                            <option value="open">Abiertos</option>
                            <option value="resolved">Resueltos</option>
                            <option value="closed">Cerrados</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Filtrar por Tipo</label>
                        <select class="form-select" id="filter-type">
                            <option value="all">Todos los tipos</option>
                            <option value="comment">Comentarios</option>
                            <option value="suggestion">Sugerencias</option>
                            <option value="correction">Correcciones</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Filtrar por Página</label>
                        <select class="form-select" id="filter-page">
                            <option value="all">Todas las páginas</option>
                            @foreach($pages as $page)
                            <option value="{{ $page }}">Página {{ $page }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Filtrar por Revisor</label>
                        <select class="form-select" id="filter-reviewer">
                            <option value="all">Todos los revisores</option>
                            @foreach($reviewers as $reviewer)
                            <option value="{{ $reviewer->user_id }}">{{ $reviewer->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-outline-secondary" id="reset-filters">
                                <i class="fas fa-redo"></i> Limpiar Filtros
                            </button>
                            <div class="btn-group">
                                <button class="btn btn-success" id="export-comments">
                                    <i class="fas fa-file-export"></i> Exportar Comentarios
                                </button>
                                <button class="btn btn-info" id="print-comments">
                                    <i class="fas fa-print"></i> Imprimir Resumen
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Comentarios -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Comentarios ({{ $comments->count() }})</h5>
                        <span class="badge bg-info" id="filtered-count">{{ $comments->count() }}</span>
                    </div>
                </div>
                <div class="card-body" id="comments-list">
                    @if($comments->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-comments fa-4x text-muted mb-3"></i>
                        <h4>No hay comentarios aún</h4>
                        <p class="text-muted">Los revisores aún no han agregado comentarios a este documento.</p>
                    </div>
                    @else
                        @foreach($comments as $comment)
                        <div class="comment-card card comment-status-{{ $comment->status }} comment-type-{{ $comment->type }}"
                             data-status="{{ $comment->status }}" 
                             data-type="{{ $comment->type }}"
                             data-page="{{ $comment->page_number }}"
                             data-reviewer="{{ $comment->user_id }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h6 class="mb-1">
                                                    <strong>{{ $comment->user->name }}</strong>
                                                    <small class="text-muted ms-2">
                                                        <i class="far fa-clock"></i> {{ $comment->created_at->format('d/m/Y H:i') }}
                                                    </small>
                                                </h6>
                                                <div class="mb-2">
                                                    <span class="badge bg-{{ 
                                                        $comment->status == 'open' ? 'warning' : 
                                                        ($comment->status == 'resolved' ? 'success' : 'secondary') 
                                                    }}">
                                                        {{ ucfirst($comment->status) }}
                                                    </span>
                                                    <span class="badge bg-{{ 
                                                        $comment->type == 'comment' ? 'info' : 
                                                        ($comment->type == 'suggestion' ? 'success' : 'danger') 
                                                    }}">
                                                        {{ ucfirst($comment->type) }}
                                                    </span>
                                                    <span class="badge bg-light text-dark">
                                                        <i class="fas fa-file-alt"></i> Página {{ $comment->page_number }}
                                                    </span>
                                                    @if($comment->resolved_by)
                                                    <span class="badge bg-light text-dark">
                                                        <i class="fas fa-check"></i> Resuelto por: {{ $comment->resolver->name ?? 'Usuario' }}
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="comment-actions">
                                                <div class="btn-group btn-group-sm">
                                                    @if($comment->status != 'resolved')
                                                    <button class="btn btn-outline-success resolve-comment" data-id="{{ $comment->id }}">
                                                        <i class="fas fa-check"></i> Marcar como Resuelto
                                                    </button>
                                                    @endif
                                                    <button class="btn btn-outline-info view-in-pdf" data-page="{{ $comment->page_number }}" data-x="{{ $comment->x_position }}" data-y="{{ $comment->y_position }}">
                                                        <i class="fas fa-search"></i> Ver en PDF
                                                    </button>
                                                    <button class="btn btn-outline-secondary reply-comment" data-id="{{ $comment->id }}">
                                                        <i class="fas fa-reply"></i> Responder
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <p class="mb-2">{{ $comment->content }}</p>
                                        
                                        <div class="mt-3">
                                            <small class="text-muted">
                                                <i class="fas fa-map-marker-alt"></i> Posición: {{ number_format($comment->x_position, 1) }}%, {{ number_format($comment->y_position, 1) }}%
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
                
                <!-- Resumen por Página -->
                @if($comments->isNotEmpty())
                <div class="card-footer">
                    <h6 class="mb-3">Resumen por Página</h6>
                    <div class="row">
                        @foreach($pageSummary as $page => $summary)
                        <div class="col-md-3 mb-2">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-3">
                                    <h6 class="card-title">Página {{ $page }}</h6>
                                    <div class="d-flex justify-content-between">
                                        <span class="badge bg-warning">{{ $summary['open'] }} abiertos</span>
                                        <span class="badge bg-success">{{ $summary['resolved'] }} resueltos</span>
                                        <span class="badge bg-secondary">{{ $summary['closed'] }} cerrados</span>
                                    </div>
                                    <button class="btn btn-sm btn-outline-primary w-100 mt-2 view-page-comments" data-page="{{ $page }}">
                                        <i class="fas fa-eye"></i> Ver Comentarios
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal para responder comentario -->
<div class="modal fade" id="replyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Responder Comentario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="reply-form">
                    @csrf
                    <input type="hidden" name="comment_id" id="reply-comment-id">
                    <div class="mb-3">
                        <label class="form-label">Tu respuesta</label>
                        <textarea name="reply_content" class="form-control" rows="4" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="submit-reply">Enviar Respuesta</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filtros
    const filterStatus = document.getElementById('filter-status');
    const filterType = document.getElementById('filter-type');
    const filterPage = document.getElementById('filter-page');
    const filterReviewer = document.getElementById('filter-reviewer');
    const resetFilters = document.getElementById('reset-filters');
    const commentsList = document.getElementById('comments-list');
    const filteredCount = document.getElementById('filtered-count');
    
    function applyFilters() {
        const status = filterStatus.value;
        const type = filterType.value;
        const page = filterPage.value;
        const reviewer = filterReviewer.value;
        
        let visibleCount = 0;
        
        document.querySelectorAll('.comment-card').forEach(card => {
            const cardStatus = card.dataset.status;
            const cardType = card.dataset.type;
            const cardPage = card.dataset.page;
            const cardReviewer = card.dataset.reviewer;
            
            let show = true;
            
            if (status !== 'all' && cardStatus !== status) show = false;
            if (type !== 'all' && cardType !== type) show = false;
            if (page !== 'all' && cardPage !== page) show = false;
            if (reviewer !== 'all' && cardReviewer !== reviewer) show = false;
            
            card.style.display = show ? 'block' : 'none';
            if (show) visibleCount++;
        });
        
        filteredCount.textContent = visibleCount;
    }
    
    filterStatus.addEventListener('change', applyFilters);
    filterType.addEventListener('change', applyFilters);
    filterPage.addEventListener('change', applyFilters);
    filterReviewer.addEventListener('change', applyFilters);
    
    resetFilters.addEventListener('click', function() {
        filterStatus.value = 'all';
        filterType.value = 'all';
        filterPage.value = 'all';
        filterReviewer.value = 'all';
        applyFilters();
    });
    
    // Resolver comentarios
    document.querySelectorAll('.resolve-comment').forEach(button => {
        button.addEventListener('click', async function() {
            const commentId = this.dataset.id;
            
            if (!confirm('¿Marcar este comentario como resuelto?')) return;
            
            try {
                const response = await fetch(`/documents/{{ $document->id }}/review/comments/${commentId}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ status: 'resolved' })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    location.reload();
                } else {
                    alert('Error al actualizar el comentario');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error de conexión');
            }
        });
    });
    
    // Ver en PDF
    document.querySelectorAll('.view-in-pdf').forEach(button => {
        button.addEventListener('click', function() {
            const page = this.dataset.page;
            const x = this.dataset.x;
            const y = this.dataset.y;
            
            // Abrir en la vista de revisión con la página y posición
            window.open(`/documents/{{ $document->id }}/review#page=${page}&x=${x}&y=${y}`, '_blank');
        });
    });
    
    // Ver comentarios por página
    document.querySelectorAll('.view-page-comments').forEach(button => {
        button.addEventListener('click', function() {
            const page = this.dataset.page;
            filterPage.value = page;
            applyFilters();
            
            // Scroll to comments
            document.getElementById('comments-list').scrollIntoView({ behavior: 'smooth' });
        });
    });
    
    // Responder comentario
    const replyModal = new bootstrap.Modal(document.getElementById('replyModal'));
    document.querySelectorAll('.reply-comment').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.dataset.id;
            document.getElementById('reply-comment-id').value = commentId;
            replyModal.show();
        });
    });
    
    document.getElementById('submit-reply').addEventListener('click', async function() {
        const form = document.getElementById('reply-form');
        const formData = new FormData(form);
        
        try {
            const response = await fetch('/api/comments/reply', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                alert('Respuesta enviada correctamente');
                replyModal.hide();
                form.reset();
            } else {
                alert('Error al enviar respuesta');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error de conexión');
        }
    });
    
    // Exportar comentarios
    // Exportar comentarios
document.getElementById('export-comments').addEventListener('click', function() {
    // Preparar datos para CSV
    const commentsData = [
        ['Página', 'Revisor', 'Tipo', 'Estado', 'Comentario', 'Fecha', 'Posición']
    ];
    
    // Agregar cada comentario
    @foreach($comments as $comment)
    commentsData.push([
        '{{ $comment->page_number }}',
        '{{ addslashes($comment->user->name) }}',
        '{{ $comment->type == "comment" ? "Comentario" : ($comment->type == "suggestion" ? "Sugerencia" : "Corrección") }}',
        '{{ $comment->status == "open" ? "Abierto" : ($comment->status == "resolved" ? "Resuelto" : "Cerrado") }}',
        `{{ addslashes($comment->content) }}`,
        '{{ $comment->created_at->format("d/m/Y H:i") }}',
        '{{ number_format($comment->x_position, 1) }}%, {{ number_format($comment->y_position, 1) }}%'
    ]);
    @endforeach
    
    // Crear CSV
    let csv = 'Documento,{{ addslashes($document->title) }}\n\n';
    csv += commentsData.map(row => 
        row.map(cell => `"${String(cell).replace(/"/g, '""')}"`).join(',')
    ).join('\n');
    
    // Descargar
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', 'comentarios_{{ $document->id }}.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
});
    
    // Imprimir resumen
    document.getElementById('print-comments').addEventListener('click', function() {
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
            <head>
                <title>Resumen de Comentarios - {{ $document->title }}</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    h1 { color: #333; }
                    .summary { margin: 20px 0; }
                    table { width: 100%; border-collapse: collapse; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f2f2f2; }
                </style>
            </head>
            <body>
                <h1>Resumen de Comentarios</h1>
                <p><strong>Documento:</strong> {{ $document->title }}</p>
                <p><strong>Fecha:</strong> ${new Date().toLocaleDateString()}</p>
                
                <div class="summary">
                    <h3>Estadísticas</h3>
                    <p>Total: {{ $comments->count() }}</p>
                    <p>Abiertos: {{ $comments->where('status', 'open')->count() }}</p>
                    <p>Resueltos: {{ $comments->where('status', 'resolved')->count() }}</p>
                    <p>Cerrados: {{ $comments->where('status', 'closed')->count() }}</p>
                </div>
                
                <h3>Comentarios por Página</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Página</th>
                            <th>Abiertos</th>
                            <th>Resueltos</th>
                            <th>Cerrados</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pageSummary as $page => $summary)
                        <tr>
                            <td>Página {{ $page }}</td>
                            <td>{{ $summary['open'] }}</td>
                            <td>{{ $summary['resolved'] }}</td>
                            <td>{{ $summary['closed'] }}</td>
                            <td>{{ $summary['total'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    });
});
</script>
@endsection