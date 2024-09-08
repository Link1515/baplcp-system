<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\SeasonRegistration;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class EventController extends Controller
{
    private readonly UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $yesterday = Carbon::yesterday();

        $events = Event::where('start_at', '>', $yesterday)->orderBy('start_at')->get();

        if (count($events) === 0) {
            return redirect()->back()->with('info', '目前尚無活動');
        }

        return view('events.index', compact('events'));
    }

    public function show(string $id)
    {
        $event = Event::with('season')->find($id);
        $season = $event->season;
        $memberRegistrations = EventRegistration::with('user')
            ->where('event_id', $id)
            ->where('is_non_member', 0)
            ->get();
        $nonMemberRegistrations = EventRegistration::with('user')
            ->where('event_id', $id)
            ->where('is_non_member', 1)
            ->get();
        $seasonRegistrations = SeasonRegistration::with('user')
            ->where('pass', 1)
            ->where('season_id', $season->id)
            ->get();

        $userId = 1;
        $user = User::find($userId);
        $userRegistration =
            $this->userService->getUserRegistration($userId, $id, $season->id);
        $userHasRegistered = !is_null($userRegistration['type']);

        $userFriendRegistration = EventRegistration::select('*')
            ->selectRaw('RANK() OVER (ORDER BY updated_at) as registration_rank')
            ->where('event_id', $id)
            ->where('user_id', $userId)
            ->where('is_non_member', 1)
            ->orderBy('updated_at')
            ->first();
        $userFriendHasRegistered = !is_null($userFriendRegistration);

        return view('events.show', compact(
            'event',
            'userHasRegistered',
            'userRegistration',
            'userFriendHasRegistered',
            'userFriendRegistration',
            'memberRegistrations',
            'nonMemberRegistrations',
            'seasonRegistrations'
        ));
    }

    public function showRegistrations(string $id)
    {
        return view('events.registrations');
    }
}
