@extends('layouts.app')

@section('header')
    <h1 class="mb-2 text-5xl text-center">{{ $eventGroup->title }}</h1>
    <h2 class="text-2xl text-center">{{ $eventGroup->sub_title }}</h2>
@endsection

@section('content')
    <div class="grid gap-4">
        @foreach ($events as $event)
            <a href="{{ route('events.register', ['id' => $event->id]) }}" class="btn">{{ $event->start_at }}</a>
        @endforeach
    </div>
@endsection
