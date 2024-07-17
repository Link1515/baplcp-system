@extends('layouts.app')

@php
    $eventGroup = $event->eventGroup;
@endphp

@section('header')
    <h1 class="mb-2 text-5xl text-center">{{ $eventGroup->title }}</h1>
    <h2 class="text-2xl text-center">{{ $event->start_at }}</h2>
@endsection

@section('content')
    <div class="mb-4 text-lg">
        <h3 class="border-neutral-400 pb-2 mb-2 text-xl text-center border-b">活動資訊</h3>
        <div>
            <span class="font-bold">報名開始時間</span>
            <span>{{ $eventGroup->register_start_at }}</span>
        </div>
        <div>
            <span class="font-bold">報名截止時間</span>
            <span>{{ $eventGroup->register_end_at }}</span>
        </div>
        <div>
            <span class="font-bold">費用</span>
            <span>{{ $eventGroup->price }}</span>
        </div>
        <div>
            <span class="font-bold">群內人數</span>
            <span>{{ $eventGroup->member_participants }}</span>
        </div>
        <div>
            <span class="font-bold">群外人數</span>
            <span>{{ $eventGroup->non_member_participants }}</span>
        </div>
    </div>
    <form class="grid gap-2 text-lg">
        <h3 class="border-neutral-400 pb-2 mb-2 text-xl text-center border-b">報名活動</h3>
        <label>
            <input type="checkbox" name="registerType" value="memberRegister">
            群內 +1
        </label>
        <label>
            <input type="checkbox" name="registerType" value="nonMemberRegister">
            群外 +1
        </label>
        <x-forms.input field="nonMemberName" :defaultValue="old('nonMemberName')" type="text">
            群外朋友姓名
        </x-forms.input>

        <button class="btn-submit mt-6">
            立即報名
        </button>
    </form>
@endsection
