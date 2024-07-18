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

        $registration = EventRegistration::where('user_id', $userId)->where('event_id', $id)->get();
        $memberHasRegistered = $registration->where('is_non_member', 0)->isNotEmpty();
        $memberRegistration = $memberHasRegistered ? $registration->where('is_non_member', 0)->first() : [];
        $nonMemberHasRegistered = $registration->where('is_non_member', 1)->isNotEmpty();
        $nonMemberRegistration = $nonMemberHasRegistered ? $registration->where('is_non_member', 1)->first() : [];

        $event = Event::with('eventGroup')->find($id);

        return view('event.register', compact('event', 'memberHasRegistered', 'memberRegistration', 'nonMemberHasRegistered', 'nonMemberRegistration'));
    }
}
