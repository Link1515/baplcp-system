<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function register(string $id)
    {
        $event = Event::with('eventGroup')->find($id);
        return view('event.register', compact('event'));
    }
}
