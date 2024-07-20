<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventGroupRegistration\StoreEventGroupRegistrationRequest;
use App\Models\EventGroup;
use App\Models\EventGroupRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class EventGroupRegistrationController extends Controller
{
    public function store(StoreEventGroupRegistrationRequest $request)
    {
        $validated = $request->validated();

        $eventGroup = EventGroup::find($validated['eventGroupId']);
        if (
            Carbon::now()->lte(Carbon::parse($eventGroup->register_start_at))
        ) {
            return redirect()->back()->with('error', '報名未開放');
        }

        if (Carbon::now()->gte(Carbon::parse($eventGroup->register_end_at))) {
            return redirect()->back()->with('error', '報名已截止');
        }

        $userId = $request->input('userId') ?? 1;

        $hasRegistered = EventGroupRegistration::where('user_id', $userId)->where('event_group_id', $validated['eventGroupId'])->exists();

        if ($hasRegistered) {
            return redirect()->back()->with('error', '已報名季打');
        }

        EventGroupRegistration::create([
            'user_id' => $userId,
            'event_group_id' => $validated['eventGroupId']
        ]);

        return redirect()->route('eventGroups.register', ['eventGroup' => $validated['eventGroupId']])->with('success', '報名成功');
    }
}
