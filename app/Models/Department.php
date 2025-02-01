<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class Department extends Model
{
    protected $table = 'departments';

    protected $fillable = [
        'project',
        'department_name',
        'akronim'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project', 'code');
    }
}
