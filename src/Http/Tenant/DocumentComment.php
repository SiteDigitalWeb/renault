<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentComment extends Model
{
    protected $fillable = [
        'document_id',
        'user_id',
        'reviewer_id',
        'content',
        'page_number',
        'x_position',
        'y_position',
        'type',
        'status',
        'resolved_by'
    ];

    protected $casts = [
        'x_position' => 'float',
        'y_position' => 'float',
        'resolved_at' => 'datetime'
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(DocumentReviewer::class);
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeOnPage($query, $page)
    {
        return $query->where('page_number', $page);
    }

    public function getTypeTextAttribute()
    {
        $types = [
            'comment' => 'Comentario',
            'suggestion' => 'Sugerencia',
            'correction' => 'Corrección'
        ];
        return $types[$this->type] ?? $this->type;
    }

    public function getStatusTextAttribute()
    {
        $statuses = [
            'open' => 'Abierto',
            'resolved' => 'Resuelto',
            'closed' => 'Cerrado'
        ];
        return $statuses[$this->status] ?? $this->status;
    }
}