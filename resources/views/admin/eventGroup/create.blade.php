@extends('layouts.app')


@section('js')
    @vite('resources/js/eventGroup/create.js')
@endsection

@section('header')
    <h1 class="mb-2 text-5xl text-center">BAPLCP</h1>
    <h2 class="text-2xl text-center">管理後臺 - 季打</h2>
@endsection

@section('content')
    <form x-data="{
        form: $form('post', '{{ route('eventGroups.store') }}', {
            title: '',
            subTitle: '',
            eventPrice: '',
            eventMemberParticipants: '', // new
            eventNonMemberParticipants: '', // new
            eventTime: '',
            eventDates: '',
            eventStartRegisterDayBefore: '',
            eventStartRegisterDayBeforeTime: '', // new
            eventEndRegisterDayBefore: '',
            eventEndRegisterDayBeforeTime: '', // new
    
            canRegisterAllEvent: '{{ old('formSubmitted') }}' ? Boolean({{ old('canRegisterAllEvent') }}) : true,
            eventGroupPrice: '',
            eventGroupMaxParticipants: '',
            eventGroupRegisterStartAt: '',
            eventGroupRegisterEndAt: '',
        }),
    }" class="grid gap-4">
        <input type="hidden" name="formSubmitted" value="true">
        @csrf
        <label class="flex flex-col gap-1 text-lg">
            標題
            <input id="title" x-model.fill="form.title" type="text" name="title" value="{{ old('title') }}"
                class="input @error('title') is-invalid @enderror">
            @error('title')
                <div class="text-red-600">{{ $message }}</div>
            @enderror
        </label>
        <label class="flex flex-col gap-1 text-lg">
            副標題
            <input id="subTitle" x-model.fill="form.subTitle" type="text" name="subTitle" value="{{ old('subTitle') }}"
                class="input @error('subTitle') is-invalid @enderror">
            @error('subTitle')
                <div class="text-red-600">{{ $message }}</div>
            @enderror
        </label>
        <label class="flex flex-col gap-1 text-lg">
            單次費用
            <input id="eventPrice" x-model.fill="form.eventPrice" type="number" name="eventPrice"
                value="{{ old('eventPrice') }}" class="input @error('eventPrice') is-invalid @enderror">
            @error('eventPrice')
                <div class="text-red-600">{{ $message }}</div>
            @enderror
        </label>
        <label class="flex flex-col gap-1 text-lg">
            群內人數
            <input id="eventMemberParticipants" x-model.fill="form.eventMemberParticipants" type="number"
                name="eventMemberParticipants" value="{{ old('eventMemberParticipants') }}"
                class="input @error('eventMemberParticipants') is-invalid @enderror">
            @error('eventMemberParticipants')
                <div class="text-red-600">{{ $message }}</div>
            @enderror
        </label>
        <label class="flex flex-col gap-1 text-lg">
            群外人數
            <input id="eventNonMemberParticipants" x-model.fill="form.eventNonMemberParticipants" type="number"
                name="eventNonMemberParticipants" value="{{ old('eventNonMemberParticipants') }}"
                class="input @error('eventNonMemberParticipants') is-invalid @enderror">
            @error('eventNonMemberParticipants')
                <div class="text-red-600">{{ $message }}</div>
            @enderror
        </label>
        <label class="flex flex-col gap-1 text-lg">
            活動開始時間
            <input id="eventTime" x-model="form.eventTime" type="text" name="eventTime" value="{{ old('eventTime') }}"
                data-default="{{ old('eventTime') }}" class="input @error('eventTime') is-invalid @enderror">
            @error('eventTime')
                <div class="text-red-600">{{ $message }}</div>
            @enderror
        </label>
        <label class="flex flex-col gap-1 text-lg">
            日期 (多選)
            <input id="eventDates" x-model.fill="form.eventDates" type="text" name="eventDates"
                value="{{ old('eventDates') }}" class="input @error('eventDates') is-invalid @enderror">
            @error('eventDates')
                <div class="text-red-600">{{ $message }}</div>
            @enderror
        </label>
        <label class="flex flex-col gap-2 text-lg">
            開放報名時間
            <div class="flex items-center gap-3">
                <span class="text-nowrap">每次活動</span>
                <input id="eventStartRegisterDayBefore" x-model.fill="form.eventStartRegisterDayBefore" type="number"
                    name="eventStartRegisterDayBefore" value="{{ old('eventStartRegisterDayBefore', 6) }}"
                    class="input @error('eventStartRegisterDayBefore') is-invalid @enderror">
                <span class="text-nowrap">天前的</span>
                <input id="eventStartRegisterDayBeforeTime" x-model.fill="form.eventStartRegisterDayBeforeTime"
                    data-default="{{ old('eventStartRegisterDayBeforeTime', '19:00') }}"
                    value="{{ old('eventStartRegisterDayBeforeTime', '19:00') }}" type="text"
                    name="eventStartRegisterDayBeforeTime"
                    class="input @error('eventStartRegisterDayBeforeTime') is-invalid @enderror">
            </div>
            @error('eventStartRegisterDayBefore')
                <div class="text-red-600">{{ $message }}</div>
            @enderror
            @error('eventStartRegisterDayBeforeTime')
                <div class="text-red-600">{{ $message }}</div>
            @enderror
        </label>
        <label class="flex flex-col gap-2 text-lg">
            截止報名時間
            <div class="flex items-center gap-3">
                <span class="text-nowrap">每次活動</span>
                <input id="eventEndRegisterDayBefore" x-model.fill="form.eventEndRegisterDayBefore" type="number"
                    name="eventEndRegisterDayBefore" value="{{ old('eventEndRegisterDayBefore', 5) }}"
                    class="input @error('eventEndRegisterDayBefore') is-invalid @enderror">
                <span class="text-nowrap">天前的</span>
                <input id="eventEndRegisterDayBeforeTime" x-model="form.eventEndRegisterDayBeforeTime"
                    data-default="{{ old('eventEndRegisterDayBeforeTime', '19:00') }}"
                    value="{{ old('eventEndRegisterDayBeforeTime', '19:00') }}" type="text"
                    name="eventEndRegisterDayBeforeTime"
                    class="input @error('eventEndRegisterDayBeforeTime') is-invalid @enderror">
            </div>
            @error('eventEndRegisterDayBefore')
                <div class="text-red-600">{{ $message }}</div>
            @enderror
            @error('eventEndRegisterDayBeforeTime')
                <div class="text-red-600">{{ $message }}</div>
            @enderror
        </label>

        <label class="flex items-center gap-2 text-lg">
            <input x-model="form.canRegisterAllEvent" id="canRegisterAllEvent" value="true" type="checkbox"
                name="canRegisterAllEvent">
            開放報名季打
        </label>
        <div x-show="form.canRegisterAllEvent" class="flex flex-col gap-4" x-collapse>
            <label class="flex flex-col gap-1 text-lg">
                季打費用
                <input id="eventGroupPrice" x-model.fill="form.eventGroupPrice" type="number" name="eventGroupPrice"
                    value="{{ old('eventGroupPrice') }}" class="input @error('eventGroupPrice') is-invalid @enderror">
                @error('eventGroupPrice')
                    <div class="text-red-600">{{ $message }}</div>
                @enderror
            </label>
            <label class="flex flex-col gap-1 text-lg">
                季打名額
                <input id="eventGroupMaxParticipants" x-model.fill="form.eventGroupMaxParticipants" type="number"
                    name="eventGroupMaxParticipants" value="{{ old('eventGroupMaxParticipants') }}"
                    class="input @error('eventGroupMaxParticipants') is-invalid @enderror">
                @error('eventGroupMaxParticipants')
                    <div class="text-red-600">{{ $message }}</div>
                @enderror
            </label>
            <label class="flex flex-col gap-1 text-lg">
                季打開放報名時間
                <input id="eventGroupRegisterStartAt" x-model.fill="form.eventGroupRegisterStartAt" type="text"
                    name="eventGroupRegisterStartAt" value="{{ old('eventGroupRegisterStartAt') }}"
                    class="input @error('eventGroupRegisterStartAt') is-invalid @enderror">
                @error('eventGroupRegisterStartAt')
                    <div class="text-red-600">{{ $message }}</div>
                @enderror
            </label>
            <label class="flex flex-col gap-1 text-lg">
                季打結束報名時間
                <input id="eventGroupRegisterEndAt" x-model.fill="form.eventGroupRegisterEndAt" type="text"
                    name="eventGroupRegisterEndAt" value="{{ old('eventGroupRegisterEndAt') }}"
                    class="input @error('eventGroupRegisterEndAt') is-invalid @enderror">
                @error('eventGroupRegisterEndAt')
                    <div class="text-red-600">{{ $message }}</div>
                @enderror
            </label>
        </div>

        <button class="btn-submit mt-6" :disabled="form.processing">
            建立季打
        </button>
    </form>
@endsection
