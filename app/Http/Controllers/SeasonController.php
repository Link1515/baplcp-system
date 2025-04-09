<?php

namespace App\Http\Controllers;

use App\Http\Requests\Season\StoreSeasonRequest;
use App\Http\Requests\Season\UpdateSeasonRequest;
use App\Models\Event;
use App\Models\Season;
use App\Models\SeasonRegistration;
use App\Services\EventService;
use App\Services\SeasonService;
use App\Services\UserService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SeasonController extends Controller
{
    public function __construct(
        private SeasonService $seasonService,
        private EventService $eventService,
        private UserService $userService
    ) {
    }

    public function index()
    {
        $seasons = $this->seasonService->getSeasonsAfterToday();
        return response()->json($seasons);
    }

    public function archive()
    {
        $seasons = $this->seasonService->getArchiveSeasons();
        return response()->json($seasons);
    }

    public function store(StoreSeasonRequest $request)
    {
        $validatedData = $request->validated();
        $season        = $this->seasonService->createSeasonWithEvents($validatedData);
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
        $validatedData = $request->validated();
        $this->seasonService->updateSeason($id, $validatedData);
        return response()->noContent();
    }

    public function destroy(string $id)
    {
        Season::destroy($id);
        return response()->noContent();
    }

    public function compute(
        string $id
    ) {
        $season = Season::find($id);

        if (!$season->can_register_all_events) {
            return response()->json(['message' => 'season registration is not open'], 400);
        }

        if ($season->is_computed) {
            return response()->json(['message' => 'already computed!'], 400);
        }

        $passedSeasonRegistrations = $this->seasonService->computePassedRegistartion($season);
        SeasonRegistration::whereIn('id', $passedSeasonRegistrations->pluck('id'))->update(['pass' => true]);

        // $this->userService->resetSeasonDebuff();
        // if ($passedSeasonRegistrations->count() === $season->register_all_participants) {
        //     $this->userService->setSeasonDebuff($passedSeasonRegistrations->pluck('user_id'));
        // }

        Season::where('id', $id)->update(['is_computed' => true]);

        return response()->noContent();
    }
}
