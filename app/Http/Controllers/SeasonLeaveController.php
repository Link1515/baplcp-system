<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeasonLeave\StoreSeasonLeaveRequest;
use App\Models\Event;
use App\Models\Season;
use App\Models\SeasonLeave;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SeasonLeaveController extends Controller
{
    public function store(StoreSeasonLeaveRequest $request)
    {
        $validated = $request->validated();

        $userId = 1;
        $eventId = $validated['eventId'];
        $event = Event::findOrFail($eventId);
        $seasonId = $validated['seasonId'];
        Season::findOrFail($seasonId);

        if (
            Carbon::now()->gt(Carbon::parse($event->register_end_at))
        ) {
            return response(['title' => '已超過請假時間', 'text' => '已超過請假時間，若有緊急事件需要請假，請自行私訊管理員。'], 403);
        }

        SeasonLeave::create([
            'user_id'   => $userId,
            'event_id'  => $eventId,
            'season_id' => $seasonId,
        ]);

        return response([
            'title' => '您已成功請假',
            'text'  => '若想再次報名，請重新按下立即報名並重新排隊。'
        ]);
    }
}
