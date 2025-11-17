<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class BookingRequirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'requirement_id',
    ];

    public function requirement(): BelongsTo
    {
        return $this->belongsTo(Requirement::class, 'requirement_id');
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function fieldValues(): HasMany
    {
        return $this->hasMany(BookingRequirementFieldValue::class, 'booking_requirement_id');
    }

    /**
     * Get requirement with fields and their values for this booking
     */
    public function getRequirementWithFieldValuesAttribute()
    {
        $requirement = $this->requirement;
        
        if (!$requirement) {
            return null;
        }
        

        $fields = $requirement->fields?->map(function ($field) {
            $fieldValue = $this->fieldValues
                ?->where('requirement_field_id', $field->id)
                ->first();
            
            $field->value = $fieldValue ? $fieldValue->value : '';
            $field->value_updated_at = $fieldValue ? $fieldValue->updated_at : '';
            $field->booking_requirement_id = $fieldValue ? $fieldValue->id : '';
            return $field;
        });

        $requirement->setRelation('fields', $fields);
        return $requirement;
    }
}
