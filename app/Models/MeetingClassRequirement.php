<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeetingClassRequirement extends Pivot
{
    protected $fillable = [
        'meeting_class_id',
        'requirement_id',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public $incrementing = true;
    public $timestamps = false;

    public function meetingClass(): BelongsTo
    {
        return $this->belongsTo(MeetingClass::class, 'meeting_class_id');
    }

    public function requirement(): BelongsTo
    {
        return $this->belongsTo(Requirement::class, 'requirement_id');
    }
}
