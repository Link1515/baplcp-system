<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class EventController extends Controller
{
    public function index()
    {
        $yesterday = Carbon::yesterday();

        $events = Event::where('start_at', '>', $yesterday)->orderBy('start_at')->get();
        return view('events.index', compact('events'));
    }

    public function show(string $id)
    {
        $event = Event::with('eventGroup')->find($id);
        $memberRegistrations = EventRegistration::with('user')
            ->where('event_id', $id)
            ->where('is_non_member', 0)
            ->whereNull('event_group_id')
            ->get();
        $nonMemberRegistrations = EventRegistration::with('user')
            ->where('event_id', $id)
            ->where('is_non_member', 1)
            ->whereNull('event_group_id')
            ->get();
        $eventGroupRegistrations = EventRegistration::with('user')
            ->where('event_id', $id)
            ->where('is_non_member', 0)
            ->where('event_group_id', $event->eventGroup->id)
            ->get();

        $userId = 1;
        $userRegistration = EventRegistration::where('event_id', $id)
            ->where('user_id', $userId)
            ->where('is_non_member', 0)
            ->orderBy('updated_at')
            ->first();

        $userHasRegistered = !is_null($userRegistration);

        $userFriendRegistration = EventRegistration::select('*')
            ->selectRaw('RANK() OVER (ORDER BY updated_at) as registration_rank')
            ->where('event_id', $id)
            ->where('user_id', $userId)
            ->where('is_non_member', 1)
            ->orderBy('updated_at')
            ->first();
        $userFriendHasRegistered = !is_null($userFriendRegistration);


        return view('events.show', compact('event', 'userHasRegistered', 'userRegistration', 'userFriendHasRegistered', 'userFriendRegistration', 'memberRegistrations', 'nonMemberRegistrations', 'eventGroupRegistrations'));
    }
}
