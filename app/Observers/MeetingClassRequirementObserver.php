<?php

namespace App\Observers;

use App\Models\MeetingClassRequirement;
use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\BookingRequirement;
use Illuminate\Support\Facades\Log;

class MeetingClassRequirementObserver
{
    /**
     * Handle the MeetingClassRequirement "creating" event.
     */
    public function creating(MeetingClassRequirement $meetingClassRequirement): void
    {
        Log::info('MeetingClassRequirementObserver: creating event fired', [
            'meeting_class_id' => $meetingClassRequirement->meeting_class_id,
            'requirement_id' => $meetingClassRequirement->requirement_id,
        ]);
    }

    /**
     * Handle the MeetingClassRequirement "created" event.
     */
    public function created(MeetingClassRequirement $meetingClassRequirement): void
    {
        Log::info('MeetingClassRequirementObserver: created event fired', [
            'id' => $meetingClassRequirement->id,
            'meeting_class_id' => $meetingClassRequirement->meeting_class_id,
            'requirement_id' => $meetingClassRequirement->requirement_id,
        ]);

        $this->handleNewRequirement($meetingClassRequirement);
    }

    /**
     * Handle the MeetingClassRequirement "saved" event.
     */
    public function saved(MeetingClassRequirement $meetingClassRequirement): void
    {
        Log::info('MeetingClassRequirementObserver: saved event fired', [
            'id' => $meetingClassRequirement->id,
            'meeting_class_id' => $meetingClassRequirement->meeting_class_id,
            'requirement_id' => $meetingClassRequirement->requirement_id,
            'wasRecentlyCreated' => $meetingClassRequirement->wasRecentlyCreated,
        ]);

        // Handle if this was recently created (in case created event doesn't fire)
        if ($meetingClassRequirement->wasRecentlyCreated) {
            $this->handleNewRequirement($meetingClassRequirement);
        }
    }

    /**
     * Handle new requirement being added to a meeting class.
     */
    protected function handleNewRequirement(MeetingClassRequirement $meetingClassRequirement): void
    {
        // Search for all bookings that have a status of TENTATIVE, PROSPECT or TURNED DOWN
        // that have this meeting class
        $statuses = [
            BookingStatus::TENTATIVE->value,
            BookingStatus::PROSPECT->value,
            BookingStatus::TURNED_DOWN->value,
        ];

        $bookings = Booking::where('meeting_class_id', $meetingClassRequirement->meeting_class_id)
            ->whereIn('status_id', $statuses)
            ->get();

        foreach ($bookings as $booking) {
            // Check if this booking requirement doesn't already exist
            $existing = $booking->bookingRequirements()
                ->where('requirement_id', $meetingClassRequirement->requirement_id)
                ->first();

            if (!$existing) {
                $booking->bookingRequirements()->create([
                    'requirement_id' => $meetingClassRequirement->requirement_id,
                ]);

                Log::info('MeetingClassRequirementObserver: Created BookingRequirement', [
                    'booking_id' => $booking->id,
                    'requirement_id' => $meetingClassRequirement->requirement_id,
                ]);
            }
        }
    }
}

