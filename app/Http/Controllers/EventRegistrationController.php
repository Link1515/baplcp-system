<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRegistration\StoreEventRegistrationRequest;
use App\Models\EventRegistration;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class EventRegistrationController extends Controller
{
    public function store(StoreEventRegistrationRequest $request)
    {
        $validated = $request->validated();
        $userId = 1;

        $registration = EventRegistration::where('user_id', $userId)->where('event_id', $validated['eventId'])->get();
        $memberHasRegistered = $registration->where('is_non_member', 0)->isNotEmpty();
        $nonMemberHasRegistered = $registration->where('is_non_member', 1)->isNotEmpty();

        if ($memberHasRegistered && $validated['memberRegister']) {
            return redirect()->back()->with('error', '已報名此活動');
        }
        if ($nonMemberHasRegistered && $validated['nonMemberRegister']) {
            return redirect()->back()->with('error', '每人僅限一位群外報名');
        }

        DB::transaction(function () use ($validated, $userId, $memberHasRegistered, $nonMemberHasRegistered) {
            if (!$memberHasRegistered && $validated['memberRegister']) {
                EventRegistration::create([
                    'user_id' => $userId,
                    'event_id' => $validated['eventId'],
                ]);
            }

            if (!$nonMemberHasRegistered && $validated['nonMemberRegister']) {
                EventRegistration::create([
                    'user_id' => 1,
                    'is_non_member' => true,
                    'non_member_name' => $validated['nonMemberName'],
                    'event_id' => $validated['eventId'],
                ]);
            }
        });

        return redirect()->route('events.register', ['id' => $validated['eventId']]);
    }
}
