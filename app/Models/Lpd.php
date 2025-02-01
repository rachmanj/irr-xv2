<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lpd extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Add date casting
    protected $casts = [
        'date' => 'date',
        'received_at' => 'datetime',
        'received_date' => 'date',
    ];

    public function documents()
    {
        return $this->belongsToMany(AdditionalDocument::class, 'lpd_additional_document', 'lpd_id', 'additional_document_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function additionalDocuments(): HasMany
    {
        return $this->hasMany(AdditionalDocument::class);
    }
}
