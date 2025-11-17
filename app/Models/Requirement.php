<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Requirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function fields(): HasMany
    {   
        return $this->hasMany(RequirementField::class);
    }

    public function bookingRequirements(): HasMany
    {
        return $this->hasMany(BookingRequirement::class);
    }

    public function meetingClassRequirements(): HasMany
    {
        return $this->hasMany(MeetingClassRequirement::class);
    }
}
