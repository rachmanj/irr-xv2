<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_path',
        'original_name',
        'mime_type',
        'size',
        'uploaded_by'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class)->onDelete('cascade');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
