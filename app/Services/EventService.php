<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventGroup;
use App\Models\EventRegistration;

class EventService
{
    public function insertManyBySeason($eventGroupIds, $userIds)
    {
        $events = Event::where('event_group_id', $eventGroupIds)->get();

        $registrations = [];
        foreach ($userIds as $userId) {
            foreach ($events as $event) {
                $registrations[] = [
                    'user_id' => $userId,
                    'event_id' => $event->id,
                    'event_group_id' => $eventGroupIds,
                    'is_season' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        EventRegistration::insert($registrations);
    }
}