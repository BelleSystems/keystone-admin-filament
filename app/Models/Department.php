<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'name',
        'monthly_target_type',
        'is_staffActual',
        'updated_by',
    ];

    protected $casts = [
        'is_staffActual' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
