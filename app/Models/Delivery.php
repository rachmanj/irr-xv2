<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Delivery extends Model
{
    protected $guarded = [];

    protected $casts = [
        'sent_date' => 'date',
        'received_date' => 'date',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(DeliveryDocument::class);
    }

    // Helper method to attach documents
    public function attachDocuments(array $documentIds, string $documentType)
    {
        foreach ($documentIds as $documentId) {
            $this->documents()->create([
                'documentable_id' => $documentId,
                'documentable_type' => $documentType
            ]);
        }
    }
}
