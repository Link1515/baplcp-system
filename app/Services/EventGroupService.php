<?php

namespace App\Services;

use App\Models\EventGroup;
use App\Models\EventGroupRegistration;

class EventGroupService
{
    public function computePassedRegistartion(EventGroup $eventGroup)
    {
        $allEventGroupRegistrations = EventGroupRegistration::with('user')
            ->where('event_group_id', $eventGroup->id)->get();
        $allEventGroupRegistrationsCount = $allEventGroupRegistrations->count();

        if ($allEventGroupRegistrationsCount <= $eventGroup->register_all_participants) {
            return $allEventGroupRegistrations;
        }

        $priorityEventGroupRegistrations =
            EventGroupRegistration::where('event_group_id', $eventGroup->id)
                ->whereHas('user', function ($query) {
                    $query->where('season_debuff', 0);
                })->get();
        $priorityEventGroupRegistrationsCount = $priorityEventGroupRegistrations->count();

        if ($priorityEventGroupRegistrationsCount === $eventGroup->register_all_participants) {
            return $priorityEventGroupRegistrations;
        }
        if ($priorityEventGroupRegistrationsCount > $eventGroup->register_all_participants) {
            return $priorityEventGroupRegistrations->random($eventGroup->register_all_participants);
        }

        $combinedRegistrations = $priorityEventGroupRegistrations;
        $secondaryEventGroupRegistrations =
            EventGroupRegistration::where('event_group_id', $eventGroup->id)
                ->whereHas('user', function ($query) {
                    $query->where('season_debuff', 1);
                })->get();
        $combinedRegistrations->push(...$secondaryEventGroupRegistrations->random($eventGroup->register_all_participants - $priorityEventGroupRegistrationsCount));

        return $combinedRegistrations;
    }
}