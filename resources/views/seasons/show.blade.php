@extends('layouts.app')

@php
    if ($userRegistration) {
        $deleteGroupEventRegistrationMethod = sprintf(
            "requestDelete('是否確定取消報名季打','%s', '%s')",
            route('seasonRegistrations.destroy', ['seasonRegistration' => $userRegistration->id]),
            route('seasons.index'),
        );
    }
@endphp

@section('header-back-url', route('seasons.index'))
@section('header')
    <h1 class="text-3xl text-center">{{ $season->title }}</h1>
    @vite('resources/js/season/show.js')
@endsection

@section('content')
    <div class="mb-4 text-lg">
        <h3 class="border-neutral-400 pb-2 mb-2 text-xl text-center border-b">季打資訊</h3>
        <div>
            <span class="font-bold">報名開始時間</span>
            <span id="registerStartAt">{{ $season->register_start_at }}</span>
        </div>
        <div>
            <span class="font-bold">報名截止時間</span>
            <span id="registerEndAt">{{ $season->register_end_at }}</span>
        </div>
        <div>
            <span class="font-bold">地點</span>
            <span>{{ $season->place }}</span>
        </div>
        <div>
            <span class="font-bold">費用</span>
            <span>{{ $season->register_all_price }}</span>
        </div>
        <div>
            <span class="font-bold">人數</span>
            <span>{{ $season->register_all_participants }}</span>
        </div>
    </div>

    @if ($userRegistration)
        <h2 class="py-2 mb-2 text-xl text-center text-white bg-green-500">已報名</h2>
        <button x-data @click="{{ $deleteGroupEventRegistrationMethod }}"
            class="btn-submit mb-8 text-xl bg-red-500">取消報名</button>
    @else
        <form class="mb-8 text-lg" x-data="{
            form: $form('post', '{{ route('seasonRegistrations.store') }}', {})
        }">
            @csrf
            <input type="hidden" name="seasonId" value="{{ $season->id }}">
            <span id="submitBtnPlaceholder"
                class=" h-11 bg-neutral-500 grid items-center mt-6 text-center text-white rounded select-none">
            </span>
            <button id="submitBtn" class="btn-submit mt-6 transition-colors" style="display: none"
                :disabled="form.processing" :class="form.processing && 'bg-neutral-500'">
                立即報名
            </button>
        </form>
    @endif

    <div id="registraionList" class="mb-4 text-lg" style="display: none">
        <h3 class="border-neutral-400 pb-2 mb-4 text-xl text-center border-b">報名清單</h3>

        <ol class=" pl-6 mb-6 list-decimal">
            @foreach ($memberRegistrations as $memberRegistration)
                <li>{{ $memberRegistration->user->name }}</li>
            @endforeach
        </ol>
    </div>
@endsection