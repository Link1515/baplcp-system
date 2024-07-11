<?php

namespace App\Http\Controllers;

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
        $result = $request->validated();

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
