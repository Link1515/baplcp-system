<?php

namespace App\Http\Controllers;

use App\Models\EventGroup;
use App\Models\Event;
use App\Models\EventGroupRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\EventGroup\StoreEventGroupRequest;
use App\Http\Requests\EventGroup\UpdateEventGroupRequest;

class EventGroupController extends Controller
{
    public function index()
    {
        $eventGroups = EventGroup::all();
        return view('eventGroup.index', compact('eventGroups'));
    }

    public function adminIndex()
    {
        $eventGroups = EventGroup::all();
        return view('admin.eventGroup.index', compact('eventGroups'));
    }

    public function create()
    {
        return view('admin.eventGroup.create');
    }

    public function store(StoreEventGroupRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            $eventGroup = new EventGroup();
            $eventGroup->title = $validated['title'];
            $eventGroup->sub_title = $validated['subTitle'];
            $eventGroup->price = $validated['singlePrice'];
            $eventGroup->member_participants = $validated['memberParticipants'];
            $eventGroup->non_member_participants = $validated['nonMemberParticipants'];

            $eventGroup->can_register_all_event = $validated['canRegisterAllEvent'];
            if ($eventGroup->can_register_all_event) {
                $eventGroup->register_start_at = $validated['eventGroupRegisterStartAt'];
                $eventGroup->register_end_at = $validated['eventGroupRegisterEndAt'];
                $eventGroup->register_all_participants = $validated['registerAllParticipants'];
                $eventGroup->register_all_price = $validated['registerAllParticipants'];
            }
            $eventGroup->save();

            $events = [];
            foreach ($validated['eventDates'] as $eventDate) {
                $eventStartAt = Carbon::parse($eventDate)->setTimeFromTimeString($validated['eventTime']);
                $eventRegisterStartAt =
                    $eventStartAt->copy()
                        ->subDays($validated['eventStartRegisterDayBefore'])
                        ->setTimeFromTimeString($validated['eventStartRegisterDayBeforeTime']);
                $eventRegisterEndAt =
                    $eventStartAt->copy()
                        ->subDays($validated['eventEndRegisterDayBefore'])
                        ->setTimeFromTimeString($validated['eventEndRegisterDayBeforeTime']);

                $events[] = new Event([
                    'start_at' => $eventStartAt,
                    'register_start_at' => $eventRegisterStartAt,
                    'register_end_at' => $eventRegisterEndAt,
                ]);
            }

            $eventGroup->events()->saveMany($events);
        }, 5);

        session()->flash('success', '創建成功');

        return redirect()->route('admin.eventGroups.index');
    }

    public function show(string $id)
    {

        // $events = EventGroup::find($id)->events()->get();
        $eventGroup = EventGroup::with('events')->find($id);
        $events = $eventGroup->events;
        return view('eventGroup.show', compact('eventGroup', 'events'));
    }

    public function edit(string $id)
    {
        $eventGroup = EventGroup::with('events')->find($id);
        $events = $eventGroup->events;

        return view('admin.eventGroup.edit', compact('eventGroup', 'events'));
    }

    public function update(UpdateEventGroupRequest $request, string $id)
    {
        $validated = $request->validated();
        $eventGroup = EventGroup::find($id);

        $eventGroup->title = $validated['title'];
        $eventGroup->sub_title = $validated['subTitle'];
        $eventGroup->price = $validated['singlePrice'];
        $eventGroup->member_participants = $validated['memberParticipants'];
        $eventGroup->non_member_participants = $validated['nonMemberParticipants'];

        $eventGroup->can_register_all_event = $validated['canRegisterAllEvent'];
        $eventGroup->register_start_at = $validated['eventGroupRegisterStartAt'];
        $eventGroup->register_end_at = $validated['eventGroupRegisterEndAt'];
        $eventGroup->register_all_participants = $validated['registerAllParticipants'];
        $eventGroup->register_all_price = $validated['registerAllParticipants'];
        $eventGroup->save();

        session()->flash('success', '更新成功');

        return redirect()->route('admin.eventGroups.index');
    }

    public function destroy(string $id)
    {
        EventGroup::destroy($id);
    }

    public function register(string $id)
    {
        $eventGroup = EventGroup::find($id);

        if (!$eventGroup->can_register_all_event) {
            return redirect()->back();
        }

        $userId = 1;

        $hasRegistered = EventGroupRegistration::where('user_id', $userId)->where('event_group_id', $id)->exists();

        return view('eventGroup.register', compact('eventGroup', 'hasRegistered'));
    }
}
