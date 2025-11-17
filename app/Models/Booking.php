<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'property_id',
        'status_id',
        'meeting_class_id',
        'date_start',
        'date_end',
        'booked_date',
        'decision_date',
        'cutoff_date',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
    public function meeting_class()
    {
        return $this->belongsTo(MeetingClass::class, 'meeting_class_id');
    }

    public function bookingRequirements()
    {
        if ($this->whereIn('status_id', [3])) {
            return $this->hasMany(BookingRequirement::class);
        }
        return $this->hasMany(BookingRequirement::class)
            ->whereHas('booking.meeting_class.requirements', function ($requirementsQuery) {
                $requirementsQuery->whereColumn('requirements.id', 'booking_requirements.requirement_id');
            });
    }

    public function requirements()
    {
        return $this->hasManyThrough(Requirement::class, BookingRequirement::class, 'booking_id', 'id', 'id', 'requirement_id');
    }


    /**
     * Boot Function
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($booking) {

            /**
             * Insert the requirements for the booking based on the meeting class if the meeting class is set
             */
            if ($booking->meeting_class && $booking->meeting_class->requirements) {
                foreach ($booking->meeting_class->requirements as $requirement) {
                    /**
                     * Insert the requirement for the booking
                     */
                    $bookingRequirement = $booking->bookingRequirements()->create([
                        'requirement_id' => $requirement->id
                    ]);

                    /**
                     * Insert the fields for the requirement
                     */
                    $insertFields = [];
                    foreach ($requirement->fields as $field) {
                        $insertFields[] = [
                            'booking_requirement_id' => $bookingRequirement->id,
                            'requirement_field_id' => $field->id,
                            'value' => null
                        ];
                    }
                    $bookingRequirement->fieldValues()->insert($insertFields);
                }
            }
        });
    }
}
