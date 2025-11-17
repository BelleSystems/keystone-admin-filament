<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class RequirementField extends Model
{
    use HasFactory;

    protected $fillable = [
        'requirement_id',
        'field_type',
        'label',
        'is_required',
    ];

    public function requirement(): BelongsTo
    {
        return $this->belongsTo(Requirement::class, 'requirement_id');
    }

    public function fieldValues(): HasMany
    {
        return $this->hasMany(BookingRequirementFieldValue::class, 'requirement_field_id');
    }
}
