<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentLog extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class)->where('model', 'Invoice');
    }

    public function additionalDocument()
    {
        return $this->belongsTo(AdditionalDocument::class)->where('model', 'AdditionalDocument');
    }
}
