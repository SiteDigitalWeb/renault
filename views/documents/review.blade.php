@extends('layouts.app')

@section('title', 'Revisar Documento')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf_viewer.min.css">
<style>
    #pdf-viewer {
        position: relative;
        overflow: auto;
        background-color: #525659;
        min-height: 700px;
        text-align: center;
        cursor: crosshair;
    }
    .pdf-page-canvas {
        margin: 10px auto;
        box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        display: block;
    }
    .comment-panel {
        height: calc(100vh - 200px);
        overflow-y: auto;
    }
    .comment-item {
        border-left: 4px solid;
        margin-bottom: 10px;
        background-color: #f8f9fa;
        border-radius: 0 5px 5px 0;
        transition: background-color 0.3s;
    }
    .comment-item:hover {
        background-color: #e9ecef;
    }
    .comment-type-comment { 
        border-color: #0dcaf0;
        background-color: #e3f2fd;
    }
    .comment-type-suggestion { 
        border-color: #198754;
        background-color: #e8f5e8;
    }
    .comment-type-correction { 
        border-color: #dc3545;
        background-color: #fde8e8;
    }
    .comment-marker {
        position: absolute;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        cursor: pointer;
        transform: translate(-50%, -50%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 12px;
        z-index: 1000;
        box-shadow: 0 2px 4px rgba(0,0,0,0.3);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .comment-marker:hover {
        transform: translate(-50%, -50%) scale(1.5);
        z-index: 1001;
        box-shadow: 0 4px 8px rgba(0,0,0,0.4);
    }
    .comment-marker i {
        font-size: 12px;
    }
    .page-container {
        position: relative;
        display: inline-block;
        margin: 20px 0;
    }
    .navigation-controls {
        position: sticky;
        top: 0;
        background: white;
        z-index: 1001;
        padding: 10px;
        border-bottom: 1px solid #dee2e6;
    }
    .temp-marker {
        animation: pulse 1s infinite;
    }
    @keyframes pulse {
        0% { transform: translate(-50%, -50%) scale(1); }
        50% { transform: translate(-50%, -50%) scale(1.2); }
        100% { transform: translate(-50%, -50%) scale(1); }
    }
    #position-indicator {
        font-size: 0.85rem;
        padding: 5px 10px;
        background: #f8f9fa;
        border-radius: 4px;
        margin-top: 5px;
    }
    .user-filter-badge {
        cursor: pointer;
        transition: all 0.3s;
    }
    .user-filter-badge:hover {
        transform: scale(1.1);
    }
    .user-filter-badge.active {
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25);
    }
    .comment-user-avatar {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #0d6efd;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-right: 8px;
    }
    
    /* Estilos para tooltip */
    .comment-tooltip {
        position: fixed;
        z-index: 9999;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        max-width: 300px;
        min-width: 250px;
        padding: 15px;
        border: 1px solid #dee2e6;
        display: none;
        pointer-events: none;
        animation: fadeIn 0.2s ease-out;
    }
    
    .comment-tooltip:before {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        border-width: 10px 10px 0;
        border-style: solid;
        border-color: white transparent transparent;
    }
    
    .comment-tooltip:after {
        content: '';
        position: absolute;
        bottom: -11px;
        left: 50%;
        transform: translateX(-50%);
        border-width: 10px 10px 0;
        border-style: solid;
        border-color: #dee2e6 transparent transparent;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .comment-tooltip-header {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        padding-bottom: 8px;
        border-bottom: 1px solid #eee;
    }
    
    .comment-tooltip-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #0d6efd;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-right: 10px;
    }
    
    .comment-tooltip-content {
        font-size: 14px;
        line-height: 1.5;
        margin: 0;
    }
    
    .comment-tooltip-meta {
        display: flex;
        gap: 8px;
        margin-top: 10px;
        font-size: 12px;
    }
    
    .comment-tooltip-type {
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">Revisión: {{ $document->title }}</h1>
                    <small class="text-muted">Subido por: {{ $document->uploader->name }} | 
                    Estado: <span class="badge bg-{{ 
                        $reviewer->status == 'pendiente' ? 'secondary' : 
                        ($reviewer->status == 'en_progreso' ? 'warning' : 'success') 
                    }}">{{ ucfirst($reviewer->status) }}</span>
                    
                    @if(auth()->user()->is_admin)
                    <span class="badge bg-dark ms-2">
                        <i class="fas fa-user-shield"></i> Modo Administrador
                    </span>
                    @endif
                    </small>
                </div>
                <div>
                    @if(auth()->user()->id == $document->uploaded_by || auth()->user()->is_admin)
                    <a href="{{ route('renault.documents.comments', $document) }}" class="btn btn-info me-2">
                        <i class="fas fa-list"></i> Panel de Comentarios
                    </a>
                    @endif
                    
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" 
                            data-bs-target="#completeReviewModal">
                        <i class="fas fa-check-circle"></i> Completar Revisión
                    </button>
                    <a href="{{ route('renault.documents.show', $document) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtro de usuarios (solo para admin) -->
    @if(auth()->user()->is_admin)
    @php
        // Obtener todos los usuarios que han hecho comentarios en este documento
        $commentUsers = \App\Models\DocumentComment::where('document_id', $document->id)
            ->with('user')
            ->get()
            ->pluck('user')
            ->unique('id')
            ->filter();
    @endphp
    
    @if($commentUsers->count() > 0)
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-filter"></i> Filtrar Comentarios por Usuario
                        <small class="text-muted ms-2">(Solo visible para administradores)</small>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="userFilter" id="filter-all" value="all" checked>
                            <label class="form-check-label" for="filter-all">
                                <span class="badge bg-secondary user-filter-badge">
                                    <i class="fas fa-users"></i> Todos los usuarios
                                </span>
                            </label>
                        </div>
                        
                        @foreach($commentUsers as $user)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="userFilter" id="filter-user-{{ $user->id }}" value="{{ $user->id }}">
                            <label class="form-check-label" for="filter-user-{{ $user->id }}">
                                <span class="badge bg-primary user-filter-badge">
                                    <i class="fas fa-user"></i> {{ $user->name }}
                                </span>
                            </label>
                        </div>
                        @endforeach
                        
                        <!-- Select para móviles -->
                        <div class="d-block d-md-none w-100 mt-2">
                            <select class="form-select form-select-sm" id="userFilterSelect">
                                <option value="all">Todos los usuarios</option>
                                @foreach($commentUsers as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <!-- Estadísticas rápidas -->
                    <div class="row mt-3">
                        @foreach($commentUsers as $user)
                        @php
                            $userCommentsCount = $document->comments->where('user_id', $user->id)->count();
                            $userOpenComments = $document->comments->where('user_id', $user->id)->where('status', 'open')->count();
                        @endphp
                        <div class="col-md-3 col-6 mb-2">
                            <div class="card border-0 shadow-sm user-stat-card" data-user-id="{{ $user->id }}">
                                <div class="card-body p-2">
                                    <div class="d-flex align-items-center">
                                        <div class="comment-user-avatar me-2">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <small class="d-block fw-bold">{{ $user->name }}</small>
                                            <small class="text-muted">
                                                {{ $userCommentsCount }} comentarios
                                                @if($userOpenComments > 0)
                                                <span class="badge bg-warning ms-1">{{ $userOpenComments }} abiertos</span>
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body p-0">
                    <div class="navigation-controls">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-outline-primary" id="zoom-out">
                                    <i class="fas fa-search-minus"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="zoom-reset">
                                    100%
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="zoom-in">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                            </div>
                            
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-outline-primary" id="prev-page">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <span class="btn btn-sm disabled">
                                    Página <span id="current-page">1</span> de <span id="total-pages">1</span>
                                </span>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="next-page">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                            
                            <div>
                                <a href="/{{ $document->file_path }}" 
                                   target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-external-link-alt"></i> Abrir en nueva pestaña
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div id="pdf-viewer">
                        <div class="d-flex justify-content-center align-items-center" style="height: 600px;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando PDF...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-comments"></i> Comentarios
                            @if(auth()->user()->is_admin)
                            <small class="text-muted ms-1" id="filter-indicator">(Todos)</small>
                            @endif
                        </h5>
                        <span class="badge bg-primary" id="comment-count">0</span>
                    </div>
                </div>
                <div class="card-body comment-panel" id="comments-container">
                    <div id="no-comments" class="text-center text-muted p-4">
                        <i class="fas fa-comments fa-2x mb-2"></i>
                        <p>No hay comentarios en esta página.<br>Haz clic en el documento para agregar uno.</p>
                    </div>
                </div>
                <div class="card-footer">
                    <form id="add-comment-form">
                        @csrf
                        <input type="hidden" name="page_number" id="comment-page" value="1">
                        <input type="hidden" name="x_position" id="comment-x">
                        <input type="hidden" name="y_position" id="comment-y">
                        
                        <div class="mb-2">
                            <label class="form-label">Tipo de comentario</label>
                            <select name="type" class="form-select" id="comment-type">
                                <option value="comment">📝 Comentario</option>
                                <option value="suggestion">💡 Sugerencia</option>
                                <option value="correction">✏️ Corrección</option>
                            </select>
                        </div>
                        
                        <div class="mb-2">
                            <label class="form-label">Tu comentario</label>
                            <textarea name="content" class="form-control" rows="3" 
                                      placeholder="Escribe tu comentario aquí..." 
                                      id="comment-content" required></textarea>
                            <div class="form-text">
                                <small>
                                    <i class="fas fa-mouse-pointer"></i> Haz clic en el documento para seleccionar la posición
                                </small>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" id="submit-comment">
                                <i class="fas fa-paper-plane"></i> Agregar Comentario
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearCommentForm()">
                                <i class="fas fa-times"></i> Limpiar
                            </button>
                        </div>
                        
                        <div id="position-indicator" class="mt-2 text-center">
                            <small class="text-muted">
                                <span id="current-position">Sin posición seleccionada</span>
                                <span id="page-info-display"> | Página: 1</span>
                            </small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para completar revisión -->
<div class="modal fade" id="completeReviewModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    @if(auth()->user()->is_admin)
                    <i class="fas fa-user-shield"></i> Finalizar Inspección
                    @else
                    Completar Revisión
                    @endif
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('renault.review.complete', $document) }}" method="POST" id="completeReviewForm">
                @csrf
                <div class="modal-body">
                    @if(auth()->user()->is_admin)
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Modo Administrador:</strong> Estás finalizando la inspección como administrador.
                    </div>
                    @endif
                    
                    <div class="mb-3">
                        <label for="comentario_general" class="form-label">Comentario General</label>
                        <textarea name="comentario_general" class="form-control" rows="4" 
                                  placeholder="Escribe tus comentarios generales sobre el documento..."></textarea>
                        <small class="text-muted">Opcional: Este comentario será visible para todos los revisores.</small>
                    </div>
                    
                    @if(!auth()->user()->is_admin)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Importante:</strong> Al completar la revisión, ya no podrás agregar más comentarios.
                        Asegúrate de haber revisado todo el documento.
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-{{ auth()->user()->is_admin ? 'info' : 'success' }}">
                        @if(auth()->user()->is_admin)
                        <i class="fas fa-check-double"></i> Finalizar Inspección
                        @else
                        <i class="fas fa-check-circle"></i> Completar Revisión
                        @endif
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Tooltip para comentarios -->
<div id="comment-tooltip" class="comment-tooltip">
    <div class="comment-tooltip-header">
        <div class="comment-tooltip-avatar" id="tooltip-avatar">U</div>
        <div>
            <strong id="tooltip-username">Usuario</strong>
            <div class="text-muted" style="font-size: 11px;" id="tooltip-time">hace 5 minutos</div>
        </div>
    </div>
    <p class="comment-tooltip-content" id="tooltip-content">Contenido del comentario...</p>
    <div class="comment-tooltip-meta">
        <span class="badge" id="tooltip-type">📝 Comentario</span>
        <span class="badge" id="tooltip-status">● Abierto</span>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
<script>

</script>
<script>
    // Variables globales
    let pdfDoc = null;
    let currentPage = 1;
    let scale = 1.2;
    let totalPages = 1;
    let markers = [];
    let comments = [];
    let filteredComments = [];
    const currentUserId = {{ auth()->id() }};
    const isAdmin = {{ auth()->user()->is_admin ? 'true' : 'false' }};
    let selectedUserId = 'all';
    
    // Variables para tooltips
    let tooltipTimeout = null;
    let currentTooltip = null;

    // Cargar PDF
    async function loadPDF() {
        try {
            const pdfUrl = '/{{ $document->file_path }}';
            
            console.log('Cargando PDF desde:', pdfUrl);
            
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';
            
            const loadingTask = pdfjsLib.getDocument({
                url: pdfUrl,
                withCredentials: false
            });
            
            pdfDoc = await loadingTask.promise;
            totalPages = pdfDoc.numPages;
            
            console.log('PDF cargado, total páginas:', totalPages);
            
            document.getElementById('total-pages').textContent = totalPages;
            await renderPage(currentPage);
            await loadComments();
            
        } catch (error) {
            console.error('Error cargando PDF:', error);
            showError('No se pudo cargar el PDF. Verifica que el archivo exista.');
        }
    }
    
    // Renderizar página
    async function renderPage(pageNum) {
        if (!pdfDoc || pageNum < 1 || pageNum > totalPages) return;
        
        currentPage = pageNum;
        document.getElementById('current-page').textContent = currentPage;
        document.getElementById('comment-page').value = currentPage;
        document.getElementById('page-info-display').textContent = ' | Página: ' + currentPage;
        
        const viewer = document.getElementById('pdf-viewer');
        viewer.innerHTML = '<div class="d-flex justify-content-center align-items-center" style="height: 600px;"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando página...</span></div></div>';
        
        const page = await pdfDoc.getPage(pageNum);
        const viewport = page.getViewport({ scale: scale });

        
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        
        canvas.height = viewport.height;
        canvas.width = viewport.width;
        canvas.className = 'pdf-page-canvas';
        
        const renderContext = {
            canvasContext: ctx,
            viewport: viewport
        };
        
        await page.render(renderContext).promise;
        
        const container = document.createElement('div');
        container.className = 'page-container';
        container.style.width = viewport.width + 'px';
        container.style.height = viewport.height + 'px';
        container.style.position = 'relative';
        
        container.appendChild(canvas);
        viewer.innerHTML = '';
        viewer.appendChild(container);
        
        canvas.addEventListener('click', handlePageClick);
        
        renderMarkers();
        updateCommentsPanel();
    }
    
    function handlePageClick(event) {
        const canvas = event.target;
        const rect = canvas.getBoundingClientRect();
        
        const x = ((event.clientX - rect.left) / canvas.width) * 100;
        const y = ((event.clientY - rect.top) / canvas.height) * 100;
        
        console.log('Click en:', { x, y, page: currentPage });
        
        document.getElementById('comment-x').value = x.toFixed(2);
        document.getElementById('comment-y').value = y.toFixed(2);
        document.getElementById('comment-content').focus();
        
        addTempMarker(x, y);
    }
    
    function addTempMarker(x, y) {
        removeTempMarkers();
        
        const container = document.querySelector('.page-container');
        const marker = document.createElement('div');
        marker.className = 'comment-marker temp-marker';
        marker.style.left = x + '%';
        marker.style.top = y + '%';
        marker.style.backgroundColor = getColorByType(document.getElementById('comment-type').value);
        marker.innerHTML = '<i class="fas fa-plus"></i>';
        
        container.appendChild(marker);
        
        setTimeout(() => marker.remove(), 3000);
    }
    
    function removeTempMarkers() {
        document.querySelectorAll('.temp-marker').forEach(marker => marker.remove());
    }
    
    function getColorByType(type) {
        const colors = {
            'comment': '#0dcaf0',
            'suggestion': '#198754',
            'correction': '#dc3545'
        };
        return colors[type] || '#0dcaf0';
    }
    
    // Función para obtener el icono del marcador según el tipo
    function getMarkerIcon(comment) {
        const icons = {
            'comment': 'fa-comment',
            'suggestion': 'fa-lightbulb',
            'correction': 'fa-edit'
        };
        return `<i class="fas ${icons[comment.type] || 'fa-comment'}"></i>`;
    }
    
    // Cargar comentarios
    const routes = {
    getComments: "{{ route('renault.review.comments', $document->id) }}",
};
    async function loadComments() {
    try {

        console.log('Cargando desde:', routes.getComments);

        const response = await fetch(routes.getComments, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('Error HTTP: ' + response.status);
        }

        const result = await response.json();

        console.log('Respuesta completa:', result);

        comments = result.data ?? result; // por si devuelves estructura con success/data

        applyUserFilter();
        renderMarkers();
        updateCommentsPanel();

    } catch (error) {
        console.error('Error cargando comentarios:', error);
        showNotification('Error al cargar comentarios', 'error');
    }
}

    
    // Aplicar filtro por usuario (solo para admin)
    function applyUserFilter() {
        if (!isAdmin || selectedUserId === 'all') {
            filteredComments = [...comments];
            updateFilterIndicator('Todos');
        } else {
            filteredComments = comments.filter(comment => comment.user_id == selectedUserId);
            
            const selectedUser = comments.find(c => c.user_id == selectedUserId)?.user;
            if (selectedUser) {
                updateFilterIndicator(selectedUser.name);
            }
        }
    }
    
    // Actualizar indicador de filtro
    function updateFilterIndicator(userName) {
        const indicator = document.getElementById('filter-indicator');
        if (indicator) {
            indicator.textContent = `(${userName})`;
        }
    }
    
    // Función para mostrar tooltip
    function showCommentTooltip(comment, event) {
        // Cancelar tooltip anterior
        if (tooltipTimeout) clearTimeout(tooltipTimeout);
        if (currentTooltip) hideCommentTooltip();
        
        const tooltip = document.getElementById('comment-tooltip');
        if (!tooltip) return;
        
        // Configurar datos del comentario
        document.getElementById('tooltip-avatar').textContent = comment.user.name.charAt(0);
        document.getElementById('tooltip-username').textContent = comment.user.name;
        document.getElementById('tooltip-content').textContent = comment.content;
        
        // Configurar tipo
        const typeBadge = document.getElementById('tooltip-type');
        const typeColors = {
            'comment': 'bg-info',
            'suggestion': 'bg-success',
            'correction': 'bg-danger'
        };
        const typeTexts = {
            'comment': '📝 Comentario',
            'suggestion': '💡 Sugerencia',
            'correction': '✏️ Corrección'
        };
        
        typeBadge.className = `badge ${typeColors[comment.type] || 'bg-secondary'} comment-tooltip-type`;
        typeBadge.textContent = typeTexts[comment.type] || comment.type;
        
        // Configurar estado
        const statusBadge = document.getElementById('tooltip-status');
        const statusColors = {
            'open': 'bg-warning text-dark',
            'resolved': 'bg-success',
            'closed': 'bg-secondary'
        };
        const statusTexts = {
            'open': '● Abierto',
            'resolved': '✓ Resuelto',
            'closed': '✗ Cerrado'
        };
        
        statusBadge.className = `badge ${statusColors[comment.status] || 'bg-secondary'} comment-tooltip-type`;
        statusBadge.textContent = statusTexts[comment.status] || comment.status;
        
        // Configurar tiempo
        document.getElementById('tooltip-time').textContent = formatRelativeTime(comment.created_at);
        
        // Posicionar tooltip
        const tooltipWidth = tooltip.offsetWidth;
        const tooltipHeight = tooltip.offsetHeight;
        const viewportWidth = window.innerWidth;
        const viewportHeight = window.innerHeight;
        
        // Posición inicial
        let posX = event.clientX;
        let posY = event.clientY - tooltipHeight - 20;
        
        // Ajustar si se sale por la izquierda
        if (posX < tooltipWidth / 2) {
            posX = tooltipWidth / 2 + 10;
        }
        
        // Ajustar si se sale por la derecha
        if (posX + tooltipWidth / 2 > viewportWidth) {
            posX = viewportWidth - tooltipWidth / 2 - 10;
        }
        
        // Ajustar si se sale por arriba
        if (posY < 50) {
            posY = event.clientY + 30;
            // Cambiar flecha para abajo
            tooltip.style.setProperty('--arrow-direction', 'down');
        } else {
            tooltip.style.setProperty('--arrow-direction', 'up');
        }
        
        tooltip.style.left = `${posX}px`;
        tooltip.style.top = `${posY}px`;
        tooltip.style.display = 'block';
        tooltip.style.transform = `translateX(-50%)`;
        
        currentTooltip = comment.id;
        
        document.addEventListener('keydown', handleEscapeKey);
    }
    
    // Función para ocultar tooltip
    function hideCommentTooltip() {
        const tooltip = document.getElementById('comment-tooltip');
        if (tooltip) {
            tooltip.style.display = 'none';
        }
        currentTooltip = null;
        document.removeEventListener('keydown', handleEscapeKey);
    }
    
    // Manejar tecla ESC
    function handleEscapeKey(event) {
        if (event.key === 'Escape') {
            hideCommentTooltip();
        }
    }
    
    // Formatear tiempo relativo
    function formatRelativeTime(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffMs = now - date;
        const diffSec = Math.floor(diffMs / 1000);
        const diffMin = Math.floor(diffSec / 60);
        const diffHour = Math.floor(diffMin / 60);
        const diffDay = Math.floor(diffHour / 24);
        
        if (diffSec < 60) return 'hace un momento';
        if (diffMin < 60) return `hace ${diffMin} minuto${diffMin > 1 ? 's' : ''}`;
        if (diffHour < 24) return `hace ${diffHour} hora${diffHour > 1 ? 's' : ''}`;
        if (diffDay < 7) return `hace ${diffDay} día${diffDay > 1 ? 's' : ''}`;
        
        return date.toLocaleDateString('es-ES', {
            day: 'numeric',
            month: 'short',
            year: 'numeric'
        });
    }
    
    // Renderizar marcadores con tooltips
    function renderMarkers() {
        markers.forEach(marker => marker.remove());
        markers = [];
        
        const pageComments = filteredComments.filter(c => c.page_number == currentPage);
        const container = document.querySelector('.page-container');
        
        if (!container) return;
        
        pageComments.forEach(comment => {
            const marker = document.createElement('div');
            marker.className = 'comment-marker';
            marker.style.left = comment.x_position + '%';
            marker.style.top = comment.y_position + '%';
            marker.style.backgroundColor = getColorByType(comment.type);
            marker.dataset.commentId = comment.id;
            marker.innerHTML = getMarkerIcon(comment);
            
            // Evento click
            marker.addEventListener('click', (e) => {
                e.stopPropagation();
                scrollToComment(comment.id);
            });
            
            // Eventos para tooltip
            marker.addEventListener('mouseenter', (e) => {
                if (tooltipTimeout) clearTimeout(tooltipTimeout);
                
                tooltipTimeout = setTimeout(() => {
                    showCommentTooltip(comment, e);
                }, 300);
            });
            
            marker.addEventListener('mouseleave', () => {
                if (tooltipTimeout) clearTimeout(tooltipTimeout);
                
                tooltipTimeout = setTimeout(() => {
                    if (currentTooltip === comment.id) {
                        hideCommentTooltip();
                    }
                }, 200);
            });
            
            // Mover tooltip con el mouse
            marker.addEventListener('mousemove', (e) => {
                if (currentTooltip === comment.id) {
                    const tooltip = document.getElementById('comment-tooltip');
                    if (tooltip && tooltip.style.display === 'block') {
                        const tooltipHeight = tooltip.offsetHeight;
                        let posX = e.clientX;
                        let posY = e.clientY - tooltipHeight - 20;
                        
                        if (posY < 50) {
                            posY = e.clientY + 30;
                        }
                        
                        tooltip.style.left = `${posX}px`;
                        tooltip.style.top = `${posY}px`;
                    }
                }
            });
            
            container.appendChild(marker);
            markers.push(marker);
        });
        
        // Ocultar tooltip al hacer clic en cualquier lugar
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.comment-marker') && !e.target.closest('#comment-tooltip')) {
                hideCommentTooltip();
            }
        });
    }
    
    function scrollToComment(commentId) {
        const commentElement = document.querySelector(`[data-comment-id="${commentId}"]`);
        if (commentElement) {
            commentElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
            commentElement.classList.add('highlight');
            setTimeout(() => commentElement.classList.remove('highlight'), 2000);
        }
    }
    
    // Actualizar panel de comentarios
    function updateCommentsPanel() {
        const container = document.getElementById('comments-container');
        const pageComments = filteredComments.filter(c => c.page_number == currentPage);
        
        document.getElementById('comment-count').textContent = pageComments.length;
        document.getElementById('no-comments').style.display = pageComments.length ? 'none' : 'block';
        
        if (pageComments.length === 0) {
            container.innerHTML = '<div id="no-comments" class="text-center text-muted p-4"><i class="fas fa-comments fa-2x mb-2"></i><p>No hay comentarios en esta página.<br>Haz clic en el documento para agregar uno.</p></div>';
            return;
        }
        
        container.innerHTML = '';
        
        pageComments.forEach(comment => {
            const typeText = {
                'comment': 'Comentario',
                'suggestion': 'Sugerencia',
                'correction': 'Corrección'
            }[comment.type] || comment.type;
            
            const statusColor = {
                'open': 'warning',
                'resolved': 'success',
                'closed': 'secondary'
            }[comment.status] || 'secondary';
            
            const statusText = {
                'open': 'Abierto',
                'resolved': 'Resuelto',
                'closed': 'Cerrado'
            }[comment.status] || comment.status;
            
            const statusIcon = comment.status === 'open' ? 'check' : 'undo';
            const statusAction = comment.status === 'open' ? 'Resolver' : 'Reabrir';
            const canResolve = comment.user_id === currentUserId || isAdmin;
            const createdAt = new Date(comment.created_at).toLocaleString();
            const typeClass = `comment-type-${comment.type}`;
            
            const commentHTML = `
                <div class="comment-item ${typeClass}" data-comment-id="${comment.id}">
                    <div class="p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <div class="d-flex align-items-center mb-1">
                                    <div class="comment-user-avatar">
                                        ${comment.user.name.charAt(0)}
                                    </div>
                                    <div>
                                        <strong class="user-name">${comment.user.name}</strong>
                                        <small class="text-muted ms-2">
                                            <i class="far fa-clock"></i> ${createdAt}
                                        </small>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <span class="badge bg-secondary">${typeText}</span>
                                    <span class="badge bg-${statusColor}">${statusText}</span>
                                    <span class="badge bg-light text-dark">
                                        <i class="fas fa-file-alt"></i> Página ${comment.page_number}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <p class="mb-2 comment-content">${comment.content}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                Posición: ${parseFloat(comment.x_position).toFixed(1)}%, ${parseFloat(comment.y_position).toFixed(1)}%
                            </small>
                            ${canResolve ? 
                                `<button class="btn btn-sm btn-outline-${statusColor} resolve-btn" data-id="${comment.id}">
                                    <i class="fas fa-${statusIcon}"></i> ${statusAction}
                                </button>` 
                                : ''
                            }
                        </div>
                    </div>
                </div>
            `;
            
            const div = document.createElement('div');
            div.innerHTML = commentHTML;
            container.appendChild(div.firstElementChild);
            
            const resolveBtn = div.querySelector('.resolve-btn');
            if (resolveBtn) {
                resolveBtn.addEventListener('click', function() {
                    const newStatus = comment.status === 'open' ? 'resolved' : 'open';
                    updateCommentStatus(comment.id, newStatus);
                });
            }
        });
    }
    
    // Event listeners para filtros de usuario (solo admin)
    if (isAdmin) {
        document.querySelectorAll('input[name="userFilter"]').forEach(radio => {
            radio.addEventListener('change', function() {
                selectedUserId = this.value;
                applyUserFilter();
                renderMarkers();
                updateCommentsPanel();
                
                document.querySelectorAll('.user-filter-badge').forEach(badge => {
                    badge.classList.remove('active');
                });
                if (this.id !== 'filter-all') {
                    const label = document.querySelector(`label[for="${this.id}"] .user-filter-badge`);
                    if (label) label.classList.add('active');
                }
                
                document.querySelectorAll('.user-stat-card').forEach(card => {
                    if (selectedUserId === 'all' || card.dataset.userId === selectedUserId) {
                        card.style.opacity = '1';
                        card.style.transform = 'scale(1)';
                    } else {
                        card.style.opacity = '0.5';
                        card.style.transform = 'scale(0.95)';
                    }
                });
            });
        });
        
        const userFilterSelect = document.getElementById('userFilterSelect');
        if (userFilterSelect) {
            userFilterSelect.addEventListener('change', function() {
                selectedUserId = this.value;
                applyUserFilter();
                renderMarkers();
                updateCommentsPanel();
                
                const radio = document.querySelector(`input[name="userFilter"][value="${selectedUserId}"]`);
                if (radio) radio.checked = true;
            });
        }
        
        document.querySelectorAll('.user-stat-card').forEach(card => {
            card.addEventListener('click', function() {
                const userId = this.dataset.userId;
                const radio = document.querySelector(`#filter-user-${userId}`);
                if (radio) {
                    radio.checked = true;
                    radio.dispatchEvent(new Event('change'));
                }
            });
        });
    }
    
    async function updateCommentStatus(commentId, status) {
        try {
            const response = await fetch(`/renault/documents/{{ $document->id }}/review/comments/${commentId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ status: status })
            });
            
            const result = await response.json();
            if (result.success) {
                showNotification('Estado actualizado correctamente', 'success');
                await loadComments();
            }
        } catch (error) {
            console.error('Error actualizando comentario:', error);
            showNotification('Error de conexión', 'error');
        }
    }
    
    // Enviar nuevo comentario
    document.getElementById('add-comment-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = document.getElementById('submit-comment');
        const content = document.getElementById('comment-content').value.trim();
        const x = document.getElementById('comment-x').value;
        const y = document.getElementById('comment-y').value;
        
        if (!content) {
            showNotification('Por favor, escribe un comentario', 'error');
            return;
        }
        
        if (!x || !y) {
            showNotification('Por favor, selecciona una posición en el documento', 'error');
            return;
        }
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
        
        try {
            const response = await fetch(`/renault/documents/{{ $document->id }}/review/comments`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.reset();
                document.getElementById('comment-page').value = currentPage;
                document.getElementById('current-position').textContent = 'Sin posición seleccionada';
                document.getElementById('comment-content').focus();
                
                await loadComments();
                
                showNotification('Comentario agregado correctamente', 'success');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Error de conexión', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Agregar Comentario';
        }
    });
    
    window.clearCommentForm = function() {
        document.getElementById('comment-content').value = '';
        document.getElementById('comment-x').value = '';
        document.getElementById('comment-y').value = '';
        document.getElementById('current-position').textContent = 'Sin posición seleccionada';
        document.querySelectorAll('.temp-marker').forEach(marker => marker.remove());
        document.getElementById('comment-content').focus();
    }
    
    // Controles
    document.getElementById('zoom-in').addEventListener('click', () => {
        if (scale < 3) {
            scale += 0.1;
            renderPage(currentPage);
        }
    });
    
    document.getElementById('zoom-out').addEventListener('click', () => {
        if (scale > 0.5) {
            scale -= 0.1;
            renderPage(currentPage);
        }
    });
    
    document.getElementById('zoom-reset').addEventListener('click', () => {
        scale = 1.2;
        renderPage(currentPage);
    });
    
    document.getElementById('prev-page').addEventListener('click', () => {
        if (currentPage > 1) {
            renderPage(currentPage - 1);
        }
    });
    
    document.getElementById('next-page').addEventListener('click', () => {
        if (currentPage < totalPages) {
            renderPage(currentPage + 1);
        }
    });
    
    document.getElementById('comment-type').addEventListener('change', function() {
        removeTempMarkers();
    });
    
    function showError(message) {
        const viewer = document.getElementById('pdf-viewer');
        viewer.innerHTML = `
            <div class="alert alert-danger m-4">
                <h5><i class="fas fa-exclamation-triangle"></i> Error</h5>
                <p>${message}</p>
                <a href="/documents/{{ $document->id }}/download" class="btn btn-primary">
                    <i class="fas fa-download"></i> Descargar PDF
                </a>
            </div>
        `;
    }
    
    function showNotification(message, type = 'info') {
        const alertClass = {
            'success': 'alert-success',
            'error': 'alert-danger',
            'info': 'alert-info'
        }[type];
        
        const icon = {
            'success': 'fa-check-circle',
            'error': 'fa-exclamation-circle',
            'info': 'fa-info-circle'
        }[type];
        
        const notification = document.createElement('div');
        notification.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
        notification.style.top = '20px';
        notification.style.right = '20px';
        notification.style.zIndex = '9999';
        notification.style.minWidth = '300px';
        notification.innerHTML = `
            <i class="fas ${icon} me-2"></i> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => notification.remove(), 5000);
    }
    
    document.getElementById('completeReviewForm').addEventListener('submit', function(e) {
        const message = isAdmin ? 
            '¿Estás seguro de finalizar la inspección?' :
            '¿Estás seguro de completar la revisión? No podrás agregar más comentarios.';
        
        if (!confirm(message)) {
            e.preventDefault();
        }
    });
    
    window.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowLeft' && currentPage > 1) {
            renderPage(currentPage - 1);
        } else if (e.key === 'ArrowRight' && currentPage < totalPages) {
            renderPage(currentPage + 1);
        }
    });
    
    // Agregar CSS dinámico para flechas del tooltip
    const style = document.createElement('style');
    style.textContent = `
        .comment-tooltip[style*="--arrow-direction: down"]:before {
            top: -10px;
            bottom: auto;
            border-width: 0 10px 10px;
            border-color: transparent transparent white;
        }
        .comment-tooltip[style*="--arrow-direction: down"]:after {
            top: -11px;
            bottom: auto;
            border-width: 0 10px 10px;
            border-color: transparent transparent #dee2e6;
        }
    `;
    document.head.appendChild(style);
    
    // Inicializar
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Inicializando visor PDF');
        loadPDF();
    });
</script>
@endsection