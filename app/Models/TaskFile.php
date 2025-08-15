<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskFile extends Model
{
    protected $fillable = [
        'task_id',
        'filename',
        'original_name',
        'file_path',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

   
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }
}
