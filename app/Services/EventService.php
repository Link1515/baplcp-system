<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Season;
use App\Models\EventRegistration;

class EventService
{
    public function insertManyBySeason($seasonIds, $userIds)
    {
        $events = Event::where('season_id', $seasonIds)->get();

        $registrations = [];
        foreach ($userIds as $userId) {
            foreach ($events as $event) {
                $registrations[] = [
                    'user_id' => $userId,
                    'event_id' => $event->id,
                    'season_id' => $seasonIds,
                    'is_season' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        EventRegistration::insert($registrations);
    }
}