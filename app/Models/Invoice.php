<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Invoice extends Model
{
    protected $guarded = [];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function additionalDocuments()
    {
        return $this->hasMany(AdditionalDocument::class);
    }

    public function invoiceType(): BelongsTo
    {
        return $this->belongsTo(InvoiceType::class, 'type_id');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function deliveries(): MorphToMany
    {
        return $this->morphToMany(Delivery::class, 'documentable', 'delivery_documents');
    }

    public function deliveryDocuments()
    {
        return $this->morphMany(DeliveryDocument::class, 'documentable');
    }
}
