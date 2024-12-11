<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdditionalDocumentType extends Model
{
    use HasFactory;

    protected $fillable = ['type_name'];

    public function additionalDocuments(): HasMany
    {
        return $this->hasMany(AdditionalDocument::class);
    }
}
