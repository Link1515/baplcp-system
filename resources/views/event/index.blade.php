@extends('layouts.app')

@section('header-back-url', route('home'))
@section('header')
    <h1 class="mb-2 text-5xl text-center">BAPLCP</h1>
    <h2 class="text-2xl text-center">臨打報名</h2>
@endsection

@section('content')
    <div class="grid gap-4">
        @foreach ($events as $event)
            <a href="{{ route('events.register', ['event' => $event->id]) }}" class="btn">{{ $event->start_at }}</a>
        @endforeach
    </div>
@endsection
