<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function additionalDocuments(): BelongsToMany
    {
        return $this->belongsToMany(AdditionalDocument::class, 'additional_document_invoice')
                    ->withPivot('remarks')
                    ->withTimestamps();
    }

    public function invoiceType(): BelongsTo
    {
        return $this->belongsTo(InvoiceType::class, 'type_id');
    }

    public function attachments()
    {
        return $this->hasMany(InvoiceAttachment::class);
    }

    public function spis(): BelongsToMany
    {
        return $this->belongsToMany(Spi::class, 'spi_invoice');
    }

    public function curLoc()
    {
        return $this->belongsTo(Department::class, 'cur_loc');
    }
}
