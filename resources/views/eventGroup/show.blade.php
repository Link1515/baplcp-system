@extends('layouts.app')

@section('header')
    <h1 class="mb-2 text-5xl text-center">{{ $eventGroup->title }}</h1>
    <h2 class="text-2xl text-center">{{ $eventGroup->sub_title }}</h2>
@endsection

@section('content')
    <div class="grid gap-4">
        @foreach ($events as $event)
            <a href="{{ route('events.register', ['event' => $event->id]) }}" class="btn">{{ $event->start_at }}</a>
        @endforeach
        @if ($eventGroup->can_register_all_event)
            <a href="{{ route('eventGroups.register', ['eventGroup' => $eventGroup->id]) }}" class="btn">季打報名</a>
        @endif
    </div>
@endsection
