<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InvoiceType extends Model
{
    use HasFactory;

    protected $fillable = ['type_name'];

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
