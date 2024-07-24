<?php

namespace App\Http\Controllers;

use App\Models\EventGroup;
use App\Models\Event;
use App\Models\EventGroupRegistration;
use App\Models\EventRegistration;
use App\Models\User;
use App\Services\EventGroupService;
use App\Services\EventService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\EventGroup\StoreEventGroupRequest;
use App\Http\Requests\EventGroup\UpdateEventGroupRequest;

class EventGroupController extends Controller
{
    public function index()
    {
        $eventGroups = EventGroup::where('can_register_all_events', 1)->get();
        return view('eventGroups.index', compact('eventGroups'));
    }

    public function adminIndex()
    {
        $eventGroups = EventGroup::all();
        return view('admin.eventGroups.index', compact('eventGroups'));
    }

    public function create()
    {
        return view('admin.eventGroups.create');
    }

    public function store(StoreEventGroupRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            $eventGroup = new EventGroup();
            $eventGroup->title = $validated['title'];
            $eventGroup->place = $validated['place'];
            $eventGroup->price = $validated['singlePrice'];
            $eventGroup->total_participants = $validated['totalParticipants'];
            $eventGroup->non_member_participants = $validated['nonMemberParticipants'];

            $eventGroup->can_register_all_events = $validated['canRegisterAllEvents'];
            if ($eventGroup->can_register_all_events) {
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

                $events[] = [
                    'event_group_id' => $eventGroup->id,
                    'start_at' => $eventStartAt,
                    'register_start_at' => $eventRegisterStartAt,
                    'register_end_at' => $eventRegisterEndAt,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            Event::insert($events);
        }, 5);

        session()->flash('success', '創建成功');

        return redirect()->route('admin.eventGroups.index');
    }

    public function show(string $id)
    {
        $eventGroup = EventGroup::find($id);

        if (!$eventGroup->can_register_all_events) {
            return redirect()->back();
        }

        $userId = 1;

        $userHasRegistered = EventGroupRegistration::where('user_id', $userId)->where('event_group_id', $id)->exists();
        $memberRegistrations = EventGroupRegistration::with('user')->where('event_group_id', $id)->get();

        return view('eventGroups.show', compact('eventGroup', 'userHasRegistered', 'memberRegistrations'));

    }

    public function edit(string $id)
    {
        $eventGroup = EventGroup::with('events')->find($id);
        $events = $eventGroup->events;

        return view('admin.eventGroups.edit', compact('eventGroup', 'events'));
    }

    public function update(UpdateEventGroupRequest $request, string $id)
    {
        $validated = $request->validated();
        $eventGroup = EventGroup::find($id);

        $eventGroup->title = $validated['title'];
        $eventGroup->place = $validated['place'];
        $eventGroup->price = $validated['singlePrice'];
        $eventGroup->total_participants = $validated['totalParticipants'];
        $eventGroup->non_member_participants = $validated['nonMemberParticipants'];

        $eventGroup->can_register_all_events = $validated['canRegisterAllEvents'];
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

    public function compute(
        string $id,
        EventGroupService $eventGroupService,
        EventService $eventService,
        UserService $userService
    ) {
        $eventGroup = EventGroup::find($id);

        if (!$eventGroup->can_register_all_events) {
            return 'season registration is not open';
        }

        if ($eventGroup->is_computed) {
            return 'already computed!';
        }

        $passedEventGroupRegistrations = $eventGroupService->computePassedRegistartion($eventGroup);
        EventGroupRegistration::whereIn('id', $passedEventGroupRegistrations->pluck('id'))->update(['pass' => true]);

        $eventService->insertManyBySeason($id, $passedEventGroupRegistrations->pluck('user_id'));

        $userService->resetSeasonDebuff();
        if ($passedEventGroupRegistrations->count() === $eventGroup->register_all_participants) {
            $userService->setSeasonDebuff($passedEventGroupRegistrations->pluck('user_id'));
        }

        EventGroup::where('id', $id)->update(['is_computed' => true]);

        return 'ok';
    }
}
