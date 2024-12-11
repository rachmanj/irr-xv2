<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdditionalDocument extends Model
{
    protected $guarded = [];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function additionalDocumentType(): BelongsTo
    {
        return $this->belongsTo(AdditionalDocumentType::class);
    }
}
