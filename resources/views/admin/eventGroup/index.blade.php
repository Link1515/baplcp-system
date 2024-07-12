@extends('layouts.app')

@section('header')
    <h1 class="mb-2 text-5xl text-center">BAPLCP</h1>
    <h2 class="text-2xl text-center">管理後臺 - 季打</h2>
@endsection

@section('content')
    <div class="grid gap-4">
        @foreach ($eventGroups as $eventGroup)
            <a href="{{ route('admin.eventGroups.edit', ['eventGroup' => $eventGroup->id]) }}"
                class="btn">{{ $eventGroup->title }}</a>
        @endforeach
        <a href="{{ route('admin.eventGroups.create') }}" class="btn">+</a>
    </div>
@endsection
