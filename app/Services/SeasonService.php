<?php

namespace App\Services;

use App\Exceptions\NotFoundException;
use App\Models\Event;
use App\Models\Season;
use App\Models\SeasonRegistration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SeasonService
{
    public function getSeasonsAfterToday()
    {
        $seasons = Season::whereHas('events', function ($query) {
            $query->where('start_at', '>=', Carbon::today());
        })
        ->withCount('seasonRegistrations')
        ->get();

        $filteredSeasons = $seasons->map(function ($season) {
            return [
                'id'                          => $season->id,
                'title'                       => $season->title,
                'place'                       => $season->place,
                'seasonParticipantsRemaining' => $season->register_all_participants - $season->season_registrations_count,
                'eventStartAt'                => $season->event_start_at,
                'eventEndAt'                  => $season->event_end_at,
            ];
        });

        return $filteredSeasons;
    }

    public function getArchiveSeasons()
    {
        $seasons = Season::with([
            'events' => function ($query) {
                $query->orderBy('start_at')->take(1);
            }
        ])->get();

        $seasonsGroupByYear = [];
        foreach ($seasons as $season) {
            $firstEvent = $season->events->first();
            if (empty($firstEvent)) {
                continue;
            }

            $year = Carbon::parse($firstEvent->start_at)->year;
            if (!array_key_exists($year, $seasonsGroupByYear)) {
                $seasonsGroupByYear[$year] = [];
            }
            $seasonsGroupByYear[$year] = $season->get();
        }

        return $seasonsGroupByYear;
    }

    public function createSeasonWithEvents(array $validatedData)
    {
        $season                     = new Season();
        $season->title              = $validatedData['title'];
        $season->place              = $validatedData['place'];
        $season->price              = $validatedData['singlePrice'];
        $season->total_participants = $validatedData['totalParticipants'];

        $season->event_start_at          = $validatedData['eventStartAt'];
        $season->event_end_at            = $validatedData['eventEndAt'];
        $season->can_register_all_events = $validatedData['canRegisterAllEvents'];

        if ($season->can_register_all_events) {
            $season->register_start_at         = $validatedData['seasonRegisterStartAt'];
            $season->register_end_at           = $validatedData['seasonRegisterEndAt'];
            $season->register_all_participants = $validatedData['registerAllParticipants'];
            $season->register_all_price        = $validatedData['registerAllPrice'];
        }

        $events = $this->prepareEvents($season, $validatedData);

        DB::transaction(function () use ($season, $events) {
            $season->save();
            Event::insert($events);
        });

        return $season;
    }

    private function prepareEvents(Season $season, array $validatedData): array
    {
        $events = [];
        foreach ($validatedData['eventDates'] as $eventDate) {
            $eventStartAt         = Carbon::parse($eventDate)->setTimeFromTimeString($validatedData['eventStartAt']);
            $eventRegisterStartAt = $eventStartAt->copy()
                ->subDays($validatedData['eventStartRegisterDayBefore'])
                ->setTimeFromTimeString($validatedData['eventStartRegisterDayBeforeTime']);
            $eventRegisterEndAt = $eventStartAt->copy()
                ->subDays($validatedData['eventEndRegisterDayBefore'])
                ->setTimeFromTimeString($validatedData['eventEndRegisterDayBeforeTime']);

            $events[] = [
                'season_id'         => $season->id,
                'start_at'          => $eventStartAt,
                'register_start_at' => $eventRegisterStartAt,
                'register_end_at'   => $eventRegisterEndAt,
                'created_at'        => now(),
                'updated_at'        => now(),
            ];
        }

        return $events;
    }

    public function updateSeason(string $id, array $validatedData)
    {
        $season = Season::find($id);
        if (!$season) {
            throw new NotFoundException('season not found');
        }

        $season->title              = $validatedData['title'];
        $season->place              = $validatedData['place'];
        $season->price              = $validatedData['singlePrice'];
        $season->total_participants = $validatedData['totalParticipants'];

        $season->event_start_at            = $validatedData['eventStartAt'];
        $season->event_end_at              = $validatedData['eventEndAt'];
        $season->can_register_all_events   = $validatedData['canRegisterAllEvents'];
        $season->register_start_at         = $validatedData['seasonRegisterStartAt'];
        $season->register_end_at           = $validatedData['seasonRegisterEndAt'];
        $season->register_all_participants = $validatedData['registerAllParticipants'];
        $season->register_all_price        = $validatedData['registerAllParticipants'];
        $season->save();
    }

    public function showSeasonStatus(string $id)
    {
        $season = Season::find($id);
        if (!$season) {
            throw new NotFoundException('season not found');
        }

        $events           = Event::where('season_id', $season->id)->orderBy('start_at')->get();
        $firstEventDate   = $events->first()->start_at;
        $lastEventDate    = $events->last()->start_at;
        $seasonStartMonth = Carbon::parse($firstEventDate)->month;
        $seasonEndMonth   = Carbon::parse($lastEventDate)->month;
        $seasonRangeStr   = str_pad($seasonStartMonth, 2, '0', STR_PAD_LEFT) . ' 月 ~ ' . str_pad($seasonEndMonth, 2, '0', STR_PAD_LEFT) . ' 月';

        if (!$season->can_register_all_events) {
            throw new BadRequestException('season registration is not allowed');
        }

        // TODO 目前暫時寫死
        $userId = 1;

        $userRegistration    = SeasonRegistration::where('user_id', $userId)->where('season_id', $season->id)->first();
        $memberRegistrations = SeasonRegistration::with('user')->where('season_id', $season->id)->get();

        return [
            'season'              => $season,
            'seasonRangeStr'      => $seasonRangeStr,
            'userRegistration'    => $userRegistration,
            'memberRegistrations' => $memberRegistrations
        ];
    }

    public function computePassedRegistartion(Season $season)
    {
        $allSeasonRegistrations = SeasonRegistration::with('user')
            ->where('season_id', $season->id)->get();
        $allSeasonRegistrationsCount = $allSeasonRegistrations->count();

        if ($allSeasonRegistrationsCount <= $season->register_all_participants) {
            return $allSeasonRegistrations;
        }

        $prioritySeasonRegistrations = SeasonRegistration::where('season_id', $season->id)
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

        $combinedRegistrations        = $prioritySeasonRegistrations;
        $secondarySeasonRegistrations = SeasonRegistration::where('season_id', $season->id)
                ->whereHas('user', function ($query) {
                    $query->where('season_debuff', 1);
                })->get();
        $combinedRegistrations->push(...$secondarySeasonRegistrations->random($season->register_all_participants - $prioritySeasonRegistrationsCount));

        return $combinedRegistrations;
    }
}
