<?php

namespace App\Services;

use App\Models\EventRegistration;
use App\Models\SeasonLeave;
use App\Models\SeasonRegistration;
use App\Models\User;
use Illuminate\Support\Collection;

class UserService
{
    public function resetSeasonDebuff()
    {
        User::where('season_debuff', 1)->update(['season_debuff' => false]);
    }

    public function setSeasonDebuff(Collection $ids)
    {
        User::whereIn('id', $ids)->update(['season_debuff' => true]);
    }

    public function getUserRegistration(int $userId, int $eventId, int $seasonId)
    {
        $userRegistration = [
            'type' => null,
            'data' => null
        ];

        $eventRegistration = $this->getRegistrationByEvent($userId, $eventId);

        if (!is_null($eventRegistration)) {
            $userRegistration['type'] = 'event';
            $userRegistration['data'] = $eventRegistration;
            return $userRegistration;
        }

        $seasonRegistration = $this->getRegistrationBySeason($userId, $seasonId, $eventId);

        if (!is_null($seasonRegistration)) {
            $userRegistration['type'] = 'season';
            $userRegistration['data'] = $seasonRegistration;
            return $userRegistration;
        }

        return $userRegistration;
    }

    private function getRegistrationByEvent(int $userId, int $eventId)
    {
        return EventRegistration::where('event_id', $eventId)
            ->where('user_id', $userId)
            ->where('is_non_member', 0)
            ->first();
    }

    private function getRegistrationBySeason(int $userId, int $seasonId, int $eventId)
    {
        $isLeave = SeasonLeave::where('user_id', $userId)->where('season_id', $seasonId)->where('event_id', $eventId)->exists();

        if ($isLeave) {
            return null;
        }

        return SeasonRegistration::where('season_id', $seasonId)
            ->where('user_id', $userId)
            ->first();
    }
}
