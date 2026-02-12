<?php

namespace Sitedigitalweb\Renault;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Sitedigitalweb\Renault\DocumentReviewer;
use Sitedigitalweb\Renault\User;

class Document extends Model
{
    use SoftDeletes;
    use UsesTenantConnection;

    protected $fillable = [
        'title',
        'file_path',
        'original_name',
        'file_size',
        'status',
        'uploaded_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function reviewers()
    {
        return $this->hasMany(DocumentReviewer::class);
    }

    public function comments()
    {
        return $this->hasMany(DocumentComment::class);
    }

    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'document_reviewers', 'document_id', 'user_id')
                    ->withPivot('status', 'comentario_general')
                    ->withTimestamps();
    }

    public function isAssignedTo($userId)
    {
        return $this->reviewers()->where('user_id', $userId)->exists();
    }

    public function getReviewStatusForUser($userId)
    {
        $reviewer = $this->reviewers()->where('user_id', $userId)->first();
        return $reviewer ? $reviewer->status : null;
    }

 

    public function getPublicUrlAttribute()
{
    return url('/' . $this->file_path);
}

public function getFullPathAttribute()
{
    return public_path($this->file_path);
}

public function fileExists()
{
    return file_exists($this->full_path);
}
}


