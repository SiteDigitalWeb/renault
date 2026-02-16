<?php

namespace Sitedigitalweb\Renault\Http;

use App\Http\Controllers\Controller;
use Sitedigitalweb\Renault\Document;
use Sitedigitalweb\Renault\DocumentReviewer;
use Sitedigitalweb\Renault\DocumentComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Hyn\Tenancy\Models\Hostname;
use Hyn\Tenancy\Models\Website;
use Hyn\Tenancy\Repositories\HostnameRepository;
use Hyn\Tenancy\Repositories\WebsiteRepository;


class DocumentReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function review($id)
{
    $user = Auth::user();

    $document = Document::with(['comments'])
        ->findOrFail($id);

    if (!$user->is_admin && $document->uploaded_by != $user->id) {

        $reviewer = DocumentReviewer::where('document_id', $document->id)
            ->where('user_id', $user->id)
            ->first();

        if (!$reviewer) {
            abort(403, 'No tienes permisos para revisar este documento');
        }
    }

    if ($user->is_admin || $document->uploaded_by == $user->id) {

        $reviewer = DocumentReviewer::firstOrCreate(
            [
                'document_id' => $document->id,
                'user_id' => $user->id
            ],
            [
                'status' => 'en_progreso',
                'assigned_at' => now()
            ]
        );

    } else {

        $reviewer = DocumentReviewer::where('document_id', $document->id)
            ->where('user_id', $user->id)
            ->firstOrFail();
    }

    $document->load(['comments' => function($query) {
        $query->orderBy('page_number')
              ->orderBy('created_at');
    }]);

    return view('renault::documents.review', compact('document', 'reviewer'));
}

   public function addComment(Request $request, $id)
{
    // ✅ Validar datos
    $request->validate([
        'content' => 'required|string',
        'page_number' => 'required|integer|min:1',
        'x_position' => 'required|numeric',
        'y_position' => 'required|numeric',
        'type' => 'required|in:comment,suggestion,correction'
    ]);

    // 🔎 Buscar documento manualmente
    $document = Document::findOrFail($id);

    // 🔐 Usuario autenticado
    $userId = auth()->id();

    // 🔎 Verificar que el usuario sea revisor del documento
    $reviewer = DocumentReviewer::where('document_id', $document->id)
        ->where('user_id', $userId)
        ->firstOrFail();

    // ✅ Crear comentario
    $comment = DocumentComment::create([
        'document_id' => $document->id,
        'user_id' => $userId,
        'reviewer_id' => $reviewer->id,
        'content' => $request->content,
        'page_number' => $request->page_number,
        'x_position' => $request->x_position,
        'y_position' => $request->y_position,
        'type' => $request->type,
        'status' => 'open'
    ]);

    // 🔄 Si estaba pendiente → pasa a en_progreso
    if ($reviewer->status === 'pendiente') {
        $reviewer->update([
            'status' => 'en_progreso'
        ]);
    }

    return response()->json([
        'success' => true,
        'comment' => $comment->load('user')
    ]);
}


 public function updateCommentStatus(Request $request, $id)
{
    // ✅ Validar datos
    $request->validate([
        'status' => 'required|in:open,resolved,closed'
    ]);

    // 🔎 Buscar comentario manualmente
    $comment = DocumentComment::findOrFail($id);

    $updateData = [
        'status' => $request->status
    ];

    // 🔄 Si se marca como resuelto
    if ($request->status === 'resolved') {
        $updateData['resolved_by'] = auth()->id();
        $updateData['resolved_at'] = now();
    }

    $comment->update($updateData);

    return response()->json([
        'success' => true
    ]);
}


    public function completeReview(Request $request, $id)
{
    // ✅ Validar datos
    $request->validate([
        'comentario_general' => 'nullable|string|max:2000',
    ]);

    // 🔎 Buscar documento manualmente
    $document = Document::findOrFail($id);

    // 🔐 Obtener usuario autenticado
    $userId = auth()->id();

    // 🔎 Buscar asignación del revisor
    $reviewer = DocumentReviewer::where('document_id', $document->id)
        ->where('user_id', $userId)
        ->firstOrFail();

    // ✅ Actualizar revisión
    $reviewer->update([
        'status' => 'completado',
        'comentario_general' => $request->comentario_general,
        'completed_at' => now(),
    ]);

    // 🔎 Verificar si aún hay revisiones pendientes
    $hasPendingReviews = DocumentReviewer::where('document_id', $document->id)
        ->where('status', '!=', 'completado')
        ->exists();

    // ✅ Si no hay pendientes → marcar documento como revisado
    if (!$hasPendingReviews) {
        $document->update([
            'status' => 'revisado',
            'reviewed_at' => now(), // opcional
        ]);
    }

    return redirect()
        ->route('renault.documents.index')
        ->with('success', 'Revisión completada correctamente.');
}


    public function getComments($documentId)
{
    $document = Document::with([
        'comments.user'
    ])->findOrFail($documentId);

    $comments = $document->comments
        ->sortBy([
            ['page_number', 'asc'],
            ['created_at', 'asc']
        ])
        ->values();

    return response()->json([
        'success' => true,
        'count' => $comments->count(),
        'data' => $comments
    ]);
}

}