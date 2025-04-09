<?php

namespace App\Http\Controllers;

use App\Exceptions\BadRequestException;
use App\Exceptions\NotFoundException;
use App\Http\Requests\Season\StoreSeasonRequest;
use App\Http\Requests\Season\UpdateSeasonRequest;
use App\Models\Season;
use App\Models\SeasonRegistration;
use App\Services\EventService;
use App\Services\SeasonService;
use App\Services\UserService;
use Illuminate\Http\Response;

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
        try {
            $seasonStatus = $this->seasonService->showSeasonStatus($id);
            return response()->json($seasonStatus);
        } catch (NotFoundException|BadRequestException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function update(UpdateSeasonRequest $request, string $id)
    {
        $validatedData = $request->validated();
        try {
            $this->seasonService->updateSeason($id, $validatedData);
            return response()->noContent();
        } catch (NotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
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
            return response()->json(['message' => 'season registration is not allowed'], 400);
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
