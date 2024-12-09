<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $guarded = [];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function additionalDocuments()
    {
        return $this->hasMany(AdditionalDocument::class);
    }

    public function attachments()
    {
        return $this->hasMany(InvoiceAttachment::class);
    }
}
