<?php

namespace App\Http\Controllers;

use App\Http\Requests\Season\StoreSeasonRequest;
use App\Http\Requests\Season\UpdateSeasonRequest;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Season;
use App\Models\SeasonRegistration;
use App\Models\User;
use App\Services\EventService;
use App\Services\SeasonService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SeasonController extends Controller
{
    public function index()
    {
        $seasons = Season::whereHas('events', function ($query) {
            $query->where('start_at', '>=', Carbon::today());
        })
        ->withCount('seasonRegistrations')
        ->get();

        $data = $seasons->map(function ($season) {
            return [
                'id'                          => $season->id,
                'title'                       => $season->title,
                'place'                       => $season->place,
                'seasonParticipantsRemaining' => $season->register_all_participants - $season->season_registrations_count,
                'eventStartAt'                => $season->event_start_at,
                'eventEndAt'                  => $season->event_end_at,
            ];
        });
        return response()->json($data);
    }

    public function archive()
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

        return response()->json($seasonsGroupByYear);
    }

    public function store(StoreSeasonRequest $request)
    {
        $validated = $request->validated();
        $season    = new Season();

        DB::transaction(function () use ($validated, $season) {
            $season->title              = $validated['title'];
            $season->place              = $validated['place'];
            $season->price              = $validated['singlePrice'];
            $season->total_participants = $validated['totalParticipants'];

            $season->event_start_at          = $validated['eventStartAt'];
            $season->event_end_at            = $validated['eventEndAt'];
            $season->can_register_all_events = $validated['canRegisterAllEvents'];
            if ($season->can_register_all_events) {
                $season->register_start_at         = $validated['seasonRegisterStartAt'];
                $season->register_end_at           = $validated['seasonRegisterEndAt'];
                $season->register_all_participants = $validated['registerAllParticipants'];
                $season->register_all_price        = $validated['registerAllPrice'];
            }
            $season->save();

            $events = [];
            foreach ($validated['eventDates'] as $eventDate) {
                $eventStartAt         = Carbon::parse($eventDate)->setTimeFromTimeString($validated['eventStartAt']);
                $eventRegisterStartAt = $eventStartAt->copy()
                        ->subDays($validated['eventStartRegisterDayBefore'])
                        ->setTimeFromTimeString($validated['eventStartRegisterDayBeforeTime']);
                $eventRegisterEndAt = $eventStartAt->copy()
                        ->subDays($validated['eventEndRegisterDayBefore'])
                        ->setTimeFromTimeString($validated['eventEndRegisterDayBeforeTime']);

                $events[] = [
                    'season_id'         => $season->id,
                    'start_at'          => $eventStartAt,
                    'register_start_at' => $eventRegisterStartAt,
                    'register_end_at'   => $eventRegisterEndAt,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ];
            }

            Event::insert($events);
        });

        return response()->json($season);
    }

    public function show(string $id)
    {
        $season           = Season::find($id);
        $events           = Event::where('season_id', $id)->orderBy('start_at')->get();
        $firstEventDate   = $events->first()->start_at;
        $lastEventDate    = $events->last()->start_at;
        $seasonStartMonth = Carbon::parse($firstEventDate)->month;
        $seasonEndMonth   = Carbon::parse($lastEventDate)->month;
        $seasonRangeStr   = str_pad($seasonStartMonth, 2, '0', STR_PAD_LEFT) . ' 月 ~ ' . str_pad($seasonEndMonth, 2, '0', STR_PAD_LEFT) . ' 月';

        if (!$season->can_register_all_events) {
            return redirect()->back();
        }

        $userId = 1;

        $userRegistration    = SeasonRegistration::where('user_id', $userId)->where('season_id', $id)->first();
        $memberRegistrations = SeasonRegistration::with('user')->where('season_id', $id)->get();

        return view('seasons.show', compact('season', 'seasonRangeStr', 'firstEventDate', 'userRegistration', 'memberRegistrations'));
    }

    public function update(UpdateSeasonRequest $request, string $id)
    {
        $validated = $request->validated();
        $season    = Season::find($id);

        $season->title              = $validated['title'];
        $season->place              = $validated['place'];
        $season->price              = $validated['singlePrice'];
        $season->total_participants = $validated['totalParticipants'];

        $season->event_start_at            = $validated['eventStartAt'];
        $season->event_end_at              = $validated['eventEndAt'];
        $season->can_register_all_events   = $validated['canRegisterAllEvents'];
        $season->register_start_at         = $validated['seasonRegisterStartAt'];
        $season->register_end_at           = $validated['seasonRegisterEndAt'];
        $season->register_all_participants = $validated['registerAllParticipants'];
        $season->register_all_price        = $validated['registerAllParticipants'];
        $season->save();

        return response()->noContent();
    }

    public function destroy(string $id)
    {
        Season::destroy($id);
        return response()->noContent();
    }

    public function compute(
        string $id,
        SeasonService $seasonService,
        EventService $eventService,
        UserService $userService
    ) {
        $season = Season::find($id);

        if (!$season->can_register_all_events) {
            return 'season registration is not open';
        }

        if ($season->is_computed) {
            return 'already computed!';
        }

        $passedSeasonRegistrations = $seasonService->computePassedRegistartion($season);
        SeasonRegistration::whereIn('id', $passedSeasonRegistrations->pluck('id'))->update(['pass' => true]);

        // $userService->resetSeasonDebuff();
        // if ($passedSeasonRegistrations->count() === $season->register_all_participants) {
        //     $userService->setSeasonDebuff($passedSeasonRegistrations->pluck('user_id'));
        // }

        Season::where('id', $id)->update(['is_computed' => true]);

        return 'ok';
    }
}
