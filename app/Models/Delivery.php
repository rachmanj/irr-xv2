<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Delivery extends Model
{
    protected $fillable = [
        'delivery_number',
        'date_sent',
        'date_received',
        'origin_project',
        'destination_project',
        'creator_id',
        'receiver_id',
        'attention_person',
        'delivery_type',
        'notes'
    ];

    protected $casts = [
        'date_sent' => 'date',
        'date_received' => 'date',
    ];

    public function originProject(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'origin_project', 'code');
    }

    public function destinationProject(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'destination_project', 'code');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function invoices(): MorphToMany
    {
        return $this->belongsToMany(Invoice::class, 'delivery_documents')
            ->withTimestamps();
    }

    public function additionalDocuments(): MorphToMany
    {
        return $this->morphedByMany(AdditionalDocument::class, 'documentable', 'delivery_documents');
    }
}
