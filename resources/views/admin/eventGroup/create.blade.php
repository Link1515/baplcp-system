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
            title: '{{ old('title') }}',
            subTitle: '{{ old('subTitle') }}',
            eventPrice: '{{ old('eventPrice') }}',
            eventMaxParticipants: '{{ old('eventMaxParticipants', 18) }}',
            eventTime: '{{ old('eventTime') }}',
            eventDates: '{{ old('eventDates') }}',
            eventStartRegisterDayBefore: '{{ old('eventStartRegisterDayBefore', 6) }}',
            eventEndRegisterDayBefore: '{{ old('eventEndRegisterDayBefore', 5) }}',

            canRegisterAllEvent: '{{ old('formSubmitted') }}' ? Boolean({{ old('canRegisterAllEvent') }}) : true,
            eventGroupPrice: '{{ old('eventGroupPrice') }}',
            eventGroupMaxParticipants: '{{ old('eventGroupMaxParticipants') }}',
            eventGroupRegisterStartAt: '{{ old('eventGroupRegisterStartAt') }}',
            eventGroupRegisterEndAt: '{{ old('eventGroupRegisterEndAt') }}',
        }),
    }" class="grid gap-4">
        <input type="hidden" name="formSubmitted" value="true">
        @csrf
        <label for="title" class="flex flex-col gap-1 text-lg">
            標題
            <input id="title" x-model="form.title" type="text" name="title"
                class="input @error('title') is-invalid @enderror">
            @error('title')
                <div class="text-red-600">{{ $message }}</div>
            @enderror
        </label>
        <label for="subTitle" class="flex flex-col gap-1 text-lg">
            副標題
            <input id="subTitle" x-model="form.subTitle" type="text" name="subTitle"
                class="input @error('subTitle') is-invalid @enderror">
            @error('subTitle')
                <div class="text-red-600">{{ $message }}</div>
            @enderror
        </label>
        <label for="eventPrice" class="flex flex-col gap-1 text-lg">
            單次費用
            <input id="eventPrice" x-model="form.eventPrice" type="number" name="eventPrice"
                class="input @error('eventPrice') is-invalid @enderror">
            @error('eventPrice')
                <div class="text-red-600">{{ $message }}</div>
            @enderror
        </label>
        <label for="eventMaxParticipants" class="flex flex-col gap-1 text-lg">
            最大參與人數
            <input id="eventMaxParticipants" x-model="form.eventMaxParticipants" type="number"
                name="eventMaxParticipants" class="input @error('eventMaxParticipants') is-invalid @enderror">
            @error('eventMaxParticipants')
                <div class="text-red-600">{{ $message }}</div>
            @enderror
        </label>
        <label for="eventTime" class="flex flex-col gap-1 text-lg">
            活動開始時間
            <input id="eventTime" x-model="form.eventTime" type="text" name="eventTime"
                class="input @error('eventTime') is-invalid @enderror">
            @error('eventTime')
                <div class="text-red-600">{{ $message }}</div>
            @enderror
        </label>
        <label for="eventDates" class="flex flex-col gap-1 text-lg">
            日期 (多選)
            <input id="eventDates" x-model="form.eventDates" type="text" name="eventDates"
                class="input @error('eventDates') is-invalid @enderror">
            @error('eventDates')
                <div class="text-red-600">{{ $message }}</div>
            @enderror
        </label>
        <label for="eventStartRegisterDayBefore" class="flex flex-col gap-2 text-lg">
            開放報名時間
            <div class="flex items-center gap-3">
                <span class="text-nowrap">每次活動</span>
                <input id="eventStartRegisterDayBefore" x-model="form.eventStartRegisterDayBefore" type="number"
                    name="eventStartRegisterDayBefore"
                    class="input @error('eventStartRegisterDayBefore') is-invalid @enderror">
                <span class="text-nowrap">天前</span>
            </div>
            @error('eventStartRegisterDayBefore')
                <div class="text-red-600">{{ $message }}</div>
            @enderror
        </label>
        <label for="eventEndRegisterDayBefore" class="flex flex-col gap-2 text-lg">
            截止報名時間
            <div class="flex items-center gap-3">
                <span class="text-nowrap">每次活動</span>
                <input id="eventEndRegisterDayBefore" x-model="form.eventEndRegisterDayBefore" type="number"
                    name="eventEndRegisterDayBefore" class="input @error('eventEndRegisterDayBefore') is-invalid @enderror">
                <span class="text-nowrap">天前</span>
            </div>
            @error('eventEndRegisterDayBefore')
                <div class="text-red-600">{{ $message }}</div>
            @enderror
        </label>

        <label for="canRegisterAllEvent" class="flex items-center gap-2 text-lg">
            <input x-model="form.canRegisterAllEvent" id="canRegisterAllEvent" value="true" type="checkbox" name="canRegisterAllEvent">
            開放報名季打
        </label>
        <div x-show="form.canRegisterAllEvent" class="flex flex-col gap-4" x-collapse>
            <label for="eventGroupPrice" class="flex flex-col gap-1 text-lg">
                季打費用
                <input id="eventGroupPrice" x-model="form.eventGroupPrice" type="number" name="eventGroupPrice"
                    class="input @error('eventGroupPrice') is-invalid @enderror">
                @error('eventGroupPrice')
                    <div class="text-red-600">{{ $message }}</div>
                @enderror
            </label>
            <label for="eventGroupMaxParticipants" class="flex flex-col gap-1 text-lg">
                季打名額
                <input id="eventGroupMaxParticipants" x-model="form.eventGroupMaxParticipants" type="number"
                    name="eventGroupMaxParticipants"
                    class="input @error('eventGroupMaxParticipants') is-invalid @enderror">
                @error('eventGroupMaxParticipants')
                    <div class="text-red-600">{{ $message }}</div>
                @enderror
            </label>
            <label for="eventGroupRegisterStartAt" class="flex flex-col gap-1 text-lg">
                季打開放報名時間
                <input id="eventGroupRegisterStartAt" x-model="form.eventGroupRegisterStartAt" type="text"
                    name="eventGroupRegisterStartAt"
                    class="input @error('eventGroupRegisterStartAt') is-invalid @enderror">
                @error('eventGroupRegisterStartAt')
                    <div class="text-red-600">{{ $message }}</div>
                @enderror
            </label>
            <label for="eventGroupRegisterEndAt" class="flex flex-col gap-1 text-lg">
                季打結束報名時間
                <input id="eventGroupRegisterEndAt" x-model="form.eventGroupRegisterEndAt" type="text"
                    name="eventGroupRegisterEndAt"
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
