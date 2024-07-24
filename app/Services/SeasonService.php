<?php

namespace App\Services;

use App\Models\Season;
use App\Models\SeasonRegistration;

class SeasonService
{
    public function computePassedRegistartion(Season $season)
    {
        $allSeasonRegistrations = SeasonRegistration::with('user')
            ->where('season_id', $season->id)->get();
        $allSeasonRegistrationsCount = $allSeasonRegistrations->count();

        if ($allSeasonRegistrationsCount <= $season->register_all_participants) {
            return $allSeasonRegistrations;
        }

        $prioritySeasonRegistrations =
            SeasonRegistration::where('season_id', $season->id)
                ->whereHas('user', function ($query) {
                    $query->where('season_debuff', 0);
                })->get();
        $prioritySeasonRegistrationsCount = $prioritySeasonRegistrations->count();

        if ($prioritySeasonRegistrationsCount === $season->register_all_participants) {
            return $prioritySeasonRegistrations;
        }
        if ($prioritySeasonRegistrationsCount > $season->register_all_participants) {
            return $prioritySeasonRegistrations->random($season->register_all_participants);
        }

        $combinedRegistrations = $prioritySeasonRegistrations;
        $secondarySeasonRegistrations =
            SeasonRegistration::where('season_id', $season->id)
                ->whereHas('user', function ($query) {
                    $query->where('season_debuff', 1);
                })->get();
        $combinedRegistrations->push(...$secondarySeasonRegistrations->random($season->register_all_participants - $prioritySeasonRegistrationsCount));

        return $combinedRegistrations;
    }
}