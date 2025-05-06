<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'sap_code',
        'payment_project',
        'city',
        'address',
        'npwp',
        'is_active',
        'created_by'
    ];
}
