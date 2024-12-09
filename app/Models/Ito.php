<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ito extends Model
{
    protected $guarded = [];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class)->withDefault(null);
    }
}
