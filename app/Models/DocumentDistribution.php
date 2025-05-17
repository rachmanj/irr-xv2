<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentDistribution extends Model
{
    protected $guarded = [];

    protected $dates = [
        'sent_at',
        'received_at',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the document that is being distributed.
     */
    public function document()
    {
        return $this->morphTo(__FUNCTION__, 'document_type', 'document_id');
    }

    /**
     * Get the sender user.
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the receiver user.
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Get the source department.
     */
    public function fromDepartment()
    {
        return $this->belongsTo(Department::class, 'from_location_code', 'location_code');
    }

    /**
     * Get the destination department.
     */
    public function toDepartment()
    {
        return $this->belongsTo(Department::class, 'to_location_code', 'location_code');
    }
} 