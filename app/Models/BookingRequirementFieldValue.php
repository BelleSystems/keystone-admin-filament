<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingRequirementFieldValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_requirement_id',
        'requirement_field_id',
        'value'
    ];

    public function bookingRequirement(): BelongsTo
    {
        return $this->belongsTo(BookingRequirement::class, 'booking_requirement_id');
    }

    public function requirementField(): BelongsTo
    {
        return $this->belongsTo(RequirementField::class, 'requirement_field_id');
    }
}

