<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingClass extends Model
{
    use HasFactory;

    protected $table = 'meeting_classes';
    protected $fillable = [
        'property_id',
        'name',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function requirements()
    {
        return $this->hasManyThrough(Requirement::class, MeetingClassRequirement::class, 'meeting_class_id', 'id', 'id', 'requirement_id');
    }

    public function requirementsBelongsToMany()
    {
        return $this->belongsToMany(Requirement::class, 'meeting_class_requirements', 'meeting_class_id', 'requirement_id')
            ->using(MeetingClassRequirement::class);
    }
}
