<?php

namespace App\Http\Controllers;

use App\Models\EventGroup;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\EventGroup\StoreEventGroupRequest;

class EventGroupController extends Controller
{
    public function index()
    {
        return view('admin.eventGroup.index');
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
            $eventGroup->can_register_all_event = $validated['canRegisterAllEvent'];
            if ($eventGroup->can_register_all_event) {
                $eventGroup->price = $validated['eventGroupPrice'];
                $eventGroup->register_start_at = $validated['eventGroupRegisterStartAt'];
                $eventGroup->register_end_at = $validated['eventGroupRegisterEndAt'];
                $eventGroup->max_participants = $validated['eventGroupMaxParticipants'];
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
                    'title' => $validated['title'],
                    'sub_title' => $eventDate,
                    'price' => $validated['eventPrice'],
                    'start_at' => $eventStartAt,
                    'register_start_at' => $eventRegisterStartAt,
                    'register_end_at' => $eventRegisterEndAt,
                    'member_participants' => $validated['eventMemberParticipants'],
                    'non_member_participants' => $validated['eventNonMemberParticipants'],
                ]);
            }

            $eventGroup->events()->saveMany($events);
        }, 5);
        return redirect()->route('eventGroups.index');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
