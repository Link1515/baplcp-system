@extends('layouts.app')

@section('header')
    <h1 class="text-5xl text-center">BAPLCP</h1>
@endsection

@section('content')
    <div class="grid gap-4">
        <a href="{{ route('events.index') }}" class="border-neutral-500 py-2 text-center border rounded">臨打報名</a>
        <a href="{{ route('eventGroups.index') }}" class="border-neutral-500 py-2 text-center border rounded">季打報名</a>
        <a href="{{ route('admin.index') }}" class="border-neutral-500 py-2 text-center border rounded">管理後臺</a>
    </div>
@endsection
