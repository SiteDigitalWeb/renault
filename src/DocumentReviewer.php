<?php

namespace Sitedigitalweb\Renault;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class DocumentReviewer extends Model
{
    use UsesTenantConnection;
    
    protected $fillable = [
        'document_id',
        'user_id',
        'status',
        'comentario_general'
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(DocumentComment::class, 'reviewer_id');
    }
}