@extends('layouts.app')

@section('title', 'Subir Documento')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Subir Nuevo Documento para Revisión</h5>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('renault.documents.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Título del Documento *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="document" class="form-label">Archivo PDF *</label>
                            <input type="file" class="form-control @error('document') is-invalid @enderror" 
                                   id="document" name="document" accept=".pdf" required>
                            @error('document')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Tamaño máximo: 10MB. Solo archivos PDF.</small>
                            <div class="mt-2">
                                <div class="progress d-none" id="uploadProgress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                         role="progressbar" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Seleccionar Revisores *</label>
                            <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                                @foreach($reviewers as $reviewer)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                           name="reviewers[]" value="{{ $reviewer->id }}" 
                                           id="reviewer{{ $reviewer->id }}">
                                    <label class="form-check-label" for="reviewer{{ $reviewer->id }}">
                                        {{ $reviewer->name }} ({{ $reviewer->email }})
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @error('reviewers')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Selecciona al menos un revisor.</small>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('documents.index') }}" class="btn btn-secondary me-md-2">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-upload"></i> Subir Documento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('uploadForm');
    const submitBtn = document.getElementById('submitBtn');
    const progressBar = document.getElementById('uploadProgress');
    
    form.addEventListener('submit', function(e) {
        const fileInput = document.getElementById('document');
        const reviewers = document.querySelectorAll('input[name="reviewers[]"]:checked');
        
        // Validar archivo
        if (fileInput.files.length === 0) {
            e.preventDefault();
            alert('Por favor, selecciona un archivo PDF.');
            return;
        }
        
        // Validar revisores
        if (reviewers.length === 0) {
            e.preventDefault();
            alert('Por favor, selecciona al menos un revisor.');
            return;
        }
        
        // Mostrar progreso
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Subiendo...';
        progressBar.classList.remove('d-none');
        
        // Simular progreso
        let progress = 0;
        const interval = setInterval(() => {
            progress += 10;
            progressBar.querySelector('.progress-bar').style.width = progress + '%';
            
            if (progress >= 90) {
                clearInterval(interval);
            }
        }, 200);
    });
});
</script>
@endsection