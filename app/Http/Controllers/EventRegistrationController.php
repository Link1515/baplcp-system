<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRegistration\StoreEventRegistrationRequest;
use App\Jobs\RegisterEvent;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class EventRegistrationController extends Controller
{
    public function store(StoreEventRegistrationRequest $request)
    {
        $validated = $request->validated();

        $event = Event::find($validated['eventId']);
        if (
            Carbon::now()->lte(Carbon::parse($event->register_start_at))
        ) {
            return redirect()->back()->with('error', '報名未開放');
        }

        if (Carbon::now()->gte(Carbon::parse($event->register_end_at))) {
            return redirect()->back()->with('error', '報名已截止');
        }

        $userId = $request->input('userId') ?? 1;

        $registration = EventRegistration::where('user_id', $userId)->where('event_id', $validated['eventId'])->get();
        $memberHasRegistered = $registration->where('is_non_member', 0)->isNotEmpty();
        $nonMemberHasRegistered = $registration->where('is_non_member', 1)->isNotEmpty();

        if ($memberHasRegistered && $validated['memberRegister']) {
            return redirect()->back()->with('error', '已報名此活動');
        }
        if ($nonMemberHasRegistered && $validated['nonMemberRegister']) {
            return redirect()->back()->with('error', '每人僅限一位群外報名');
        }

        RegisterEvent::dispatch($validated, $userId, $memberHasRegistered, $nonMemberHasRegistered);

        return redirect()->route('events.register', ['event' => $validated['eventId']]);
    }
}
