<?php

namespace App\Http\Controllers;

use App\Models\EventGroup;
use Illuminate\Http\Request;
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

        $eventGroup = new EventGroup();
        $eventGroup->title = $validated['title'];
        $eventGroup->sub_title = $validated['subTitle'];
        $eventGroup->can_register_all_event = $validated['canRegisterAllEvent'];
        if ($eventGroup->can_register_all_event) {
            $eventGroup->price = $validated['eventGroupPrice'];
            $eventGroup->max_participants = $validated['eventGroupMaxParticipants'];
        }
        $eventGroup->save();

        return view('admin.eventGroup.index');
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
