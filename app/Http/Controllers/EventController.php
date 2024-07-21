<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function register(string $id)
    {
        $userId = 1;

        $userRegistration = EventRegistration::select('*')
            ->selectRaw('RANK() OVER (ORDER BY updated_at) as registration_rank')
            ->where('event_id', $id)
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

        $event = Event::with('eventGroup')->find($id);
        $memberRegistrations = EventRegistration::with('user')->where('event_id', $id)->where('is_non_member', 0)->get();
        $nonMemberRegistrations = EventRegistration::with('user')->where('event_id', $id)->where('is_non_member', 1)->get();

        return view('event.register', compact('event', 'userHasRegistered', 'userRegistration', 'userFriendHasRegistered', 'userFriendRegistration', 'memberRegistrations', 'nonMemberRegistrations'));
    }
}
