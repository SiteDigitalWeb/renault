<?php

namespace Sitedigitalweb\Renault\Http;

use App\Http\Controllers\Controller;
use Sitedigitalweb\Renault\Document;
use Sitedigitalweb\Renault\User;
use Sitedigitalweb\Renault\DocumentReviewer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

  

    public function index()
    {
        $user = Auth::user();
   
        $documents = Document::with(['uploader', 'reviewers.user'])
            ->when(!$user->hasRole('admin'), function($query) use ($user) {
                $query->where(function($q) use ($user) {
                    $q->where('uploaded_by', $user->id)
                      ->orWhereHas('reviewers', function($reviewerQuery) use ($user) {
                          $reviewerQuery->where('user_id', $user->id);
                      });
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
       
        return view('renault::documents.index', compact('documents'));
    }

    public function create()
    {
        $reviewers = User::where('id', '!=', Auth::id())->get();
        return view('renault::documents.create', compact('reviewers'));
    }

public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'document' => 'required|mimes:pdf|max:10240',
        'reviewers' => 'required|array',
    ]);

    // Directorio en public/saas/documents
    $directory = public_path('saas/documents');
    
    // Crear directorio si no existe
    if (!file_exists($directory)) {
        mkdir($directory, 0755, true);
    }

    // Subir el documento directamente a public/saas/documents
    $file = $request->file('document');
    
    // OBTENER EL TAMAÑO ANTES DE MOVER EL ARCHIVO
    $fileSize = $file->getSize();
    $originalName = $file->getClientOriginalName();
    
    $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
    $file->move($directory, $filename);
    
    // Guardar ruta relativa
    $relativePath = 'saas/documents/' . $filename;

    // Crear el documento
    $document = Document::create([
        'title' => $request->title,
        'file_path' => $relativePath,
        'original_name' => $originalName,
        'file_size' => $this->formatBytes($fileSize), // Usar tamaño ya obtenido
        'uploaded_by' => Auth::id(),
        'status' => 'pendiente'
    ]);

    foreach ($request->reviewers as $reviewerId) {
        DocumentReviewer::create([
            'document_id' => $document->id,
            'user_id' => $reviewerId,
            'status' => 'pendiente'
        ]);
    }

    return redirect()->route('renault.documents.index')
        ->with('success', 'Documento subido y revisores asignados correctamente.');
}

public function download(Document $document)
{
    // TEMPORAL: Comentar autorización
    // $this->authorize('view', $document);
    
    // Verificar manualmente
    $user = auth()->user();
    
    \Log::info('Download - Verificación manual:', [
        'user_id' => $user->id,
        'document_id' => $document->id,
        'uploaded_by' => $document->uploaded_by,
        'is_admin' => $user->is_admin
    ]);
    
    $canDownload = false;
    
    // Verificar permisos manualmente
    if ($document->uploaded_by == $user->id) {
        $canDownload = true;
        \Log::info('Usuario es uploader, permitiendo descarga');
    } 
    elseif ($document->reviewers()->where('user_id', $user->id)->exists()) {
        $canDownload = true;
        \Log::info('Usuario es revisor, permitiendo descarga');
    }
    elseif (!empty($user->is_admin) && $user->is_admin == true) {
        $canDownload = true;
        \Log::info('Usuario es admin, permitiendo descarga');
    }
    
    if (!$canDownload) {
        \Log::warning('Usuario NO autorizado para descargar documento', [
            'user_id' => $user->id,
            'document_id' => $document->id
        ]);
        abort(403, 'No tienes permiso para descargar este documento.');
    }
    
    // Ruta completa en public/saas/documents
    $filePath = public_path($document->file_path);
    
    \Log::info('Descargando archivo:', [
        'document_id' => $document->id,
        'file_path' => $document->file_path,
        'full_path' => $filePath,
        'exists' => file_exists($filePath)
    ]);
    
    if (!file_exists($filePath)) {
        \Log::error('Archivo no encontrado: ' . $filePath);
        return back()->with('error', 'El archivo no existe en: ' . $filePath);
    }
    
    return response()->download($filePath, $document->original_name);
}

public function destroy(Document $document)
{
    $this->authorize('delete', $document);
    
    // Eliminar archivo físico de public/saas/documents
    $filePath = public_path($document->file_path);
    
    if (file_exists($filePath)) {
        unlink($filePath);
    }
    
    $document->delete();
    
    return redirect()->route('documents.index')
        ->with('success', 'Documento eliminado correctamente.');
}

  public function show($id)
{
    $user = Auth::user();

    $document = Document::with(['uploader', 'reviewers.user', 'comments.user'])
        ->findOrFail($id);

    \Log::info('Manual permission check for document ' . $document->id, [
        'user_id' => $user->id,
        'is_admin' => $user->is_admin,
        'uploaded_by' => $document->uploaded_by,
        'is_uploader' => $document->uploaded_by == $user->id
    ]);

    if ($document->uploaded_by == $user->id) {
        \Log::info('User is uploader, allowing access');
    } elseif ($document->reviewers()->where('user_id', $user->id)->exists()) {
        \Log::info('User is reviewer, allowing access');
    } elseif ($user->is_admin) {
        \Log::info('User is admin, allowing access');
    } else {
        \Log::warning('User NOT authorized to view document ' . $document->id);
        abort(403, 'No tienes permiso para ver este documento.');
    }

    $reviewers = $document->reviewers;

    return view('renault::documents.show', compact('document', 'reviewers'));
}



    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}