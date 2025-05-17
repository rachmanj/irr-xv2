<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class AdditionalDocument extends Model
{
    protected $guarded = [];

    public function type(): BelongsTo
    {
        return $this->belongsTo(AdditionalDocumentType::class, 'type_id', 'id');
    }

    public function invoices(): BelongsToMany
    {
        return $this->belongsToMany(Invoice::class, 'additional_document_invoice')
                    ->withPivot('remarks')
                    ->withTimestamps();
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    /**
     * Get the supplier of the document.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
    
    /**
     * Get the distributions for the additional document.
     */
    public function distributions(): MorphMany
    {
        return $this->morphMany(DocumentDistribution::class, 'document');
    }
    
    /**
     * Get the current location department.
     */
    public function currentDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'cur_loc', 'location_code');
    }
}
