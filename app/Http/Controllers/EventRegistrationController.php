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
            Carbon::now()->lte(Carbon::parse($event->register_start_at)->subSeconds(1))
        ) {
            return redirect()->back()->with('error', '報名未開放');
        }

        if (Carbon::now()->gte(Carbon::parse($event->register_end_at))) {
            return redirect()->back()->with('error', '報名已截止');
        }

        $userId = $request->input('userId') ?? 1;

        $memberHasRegistered = EventRegistration::where('user_id', $userId)->where('event_id', $validated['eventId'])->where('is_non_member', 0)->exists();
        $nonMemberHasRegistered = EventRegistration::where('user_id', $userId)->where('event_id', $validated['eventId'])->where('is_non_member', 1)->exists();

        if ($memberHasRegistered && $validated['memberRegister']) {
            return redirect()->back()->with('error', '已報名此活動');
        }
        if ($nonMemberHasRegistered && $validated['nonMemberRegister']) {
            return redirect()->back()->with('error', '每人僅限一位群外報名');
        }

        RegisterEvent::dispatch($validated, $userId, $memberHasRegistered, $nonMemberHasRegistered);

        return redirect()->route('events.show', ['event' => $validated['eventId']])->with('success', true);
    }

    public function destroy($id)
    {
        $eventRegistration = EventRegistration::with('event')->find($id);
        $event = $eventRegistration->event;

        if (
            Carbon::now()->gt(Carbon::parse($event->register_end_at))
        ) {
            return response(['title' => '已超過取消報名時間', 'text' => '已超過取消報名時間，若有緊急事件需要取消報名，請自行私訊管理員。'], 403);
        }

        if ($eventRegistration->is_non_member) {
            $eventRegistration->forceDelete();
        } else {
            $eventRegistration->delete();
        }

        return response([
            'title' => '您已取消報名',
            'text'  => '若想再次報名，請重新按下立即報名並重新排隊。'
        ], 200);
    }
}
