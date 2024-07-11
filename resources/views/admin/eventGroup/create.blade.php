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
            priceForEvent: '{{ old('priceForEvent') }}',
            maxParticipantsForEvent: '{{ old('maxParticipantsForEvent', 18) }}',
            eventTime: '',
            dates: '{{ old('dates') }}',
            eventStartRegisterDayBefore: '{{ old('eventStartRegisterDayBefore', 6) }}',
            eventEndRegisterDayBefore: '{{ old('eventEndRegisterDayBefore', 5) }}',

            canRegisterAll: '{{ old('formSubmitted') }}' ? Boolean({{ old('canRegisterAll') }}) : true,
            priceForEventGroup: '{{ old('priceForEventGroup') }}',
            maxParticipantsForEventGroup: '{{ old('maxParticipantsForEventGroup') }}',
            registerStartDateForEventGroup: '{{ old('registerStartDateForEventGroup') }}',
            registerEndDateForEventGroup: '{{ old('registerEndDateForEventGroup') }}',
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
        <label for="priceForEvent" class="flex flex-col gap-1 text-lg">
            單次費用
            <input id="priceForEvent" x-model="form.priceForEvent" type="number" name="priceForEvent"
                class="input @error('priceForEvent') is-invalid @enderror">
            @error('priceForEvent')
                <div class="text-red-600">{{ $message }}</div>
            @enderror
        </label>
        <label for="maxParticipantsForEvent" class="flex flex-col gap-1 text-lg">
            最大參與人數
            <input id="maxParticipantsForEvent" x-model="form.maxParticipantsForEvent" type="number"
                name="maxParticipantsForEvent" class="input @error('maxParticipantsForEvent') is-invalid @enderror">
            @error('maxParticipantsForEvent')
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
        <label for="dates" class="flex flex-col gap-1 text-lg">
            日期 (多選)
            <input id="dates" x-model="form.dates" type="text" name="dates"
                class="input @error('dates') is-invalid @enderror">
            @error('dates')
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

        <label for="canRegisterAll" class="flex items-center gap-2 text-lg">
            <input x-model="form.canRegisterAll" id="canRegisterAll" value="true" type="checkbox" name="canRegisterAll">
            開放報名季打
        </label>
        <div x-show="form.canRegisterAll" class="flex flex-col gap-4" x-collapse>
            <label for="priceForEventGroup" class="flex flex-col gap-1 text-lg">
                季打費用
                <input id="priceForEventGroup" x-model="form.priceForEventGroup" type="number" name="priceForEventGroup"
                    class="input @error('priceForEventGroup') is-invalid @enderror">
                @error('priceForEventGroup')
                    <div class="text-red-600">{{ $message }}</div>
                @enderror
            </label>
            <label for="maxParticipantsForEventGroup" class="flex flex-col gap-1 text-lg">
                季打名額
                <input id="maxParticipantsForEventGroup" x-model="form.maxParticipantsForEventGroup" type="number"
                    name="maxParticipantsForEventGroup"
                    class="input @error('maxParticipantsForEventGroup') is-invalid @enderror">
                @error('maxParticipantsForEventGroup')
                    <div class="text-red-600">{{ $message }}</div>
                @enderror
            </label>
            <label for="registerStartDateForEventGroup" class="flex flex-col gap-1 text-lg">
                季打開放報名時間
                <input id="registerStartDateForEventGroup" x-model="form.registerStartDateForEventGroup" type="text"
                    name="registerStartDateForEventGroup"
                    class="input @error('registerStartDateForEventGroup') is-invalid @enderror">
                @error('registerStartDateForEventGroup')
                    <div class="text-red-600">{{ $message }}</div>
                @enderror
            </label>
            <label for="registerEndDateForEventGroup" class="flex flex-col gap-1 text-lg">
                季打結束報名時間
                <input id="registerEndDateForEventGroup" x-model="form.registerEndDateForEventGroup" type="text"
                    name="registerEndDateForEventGroup"
                    class="input @error('registerEndDateForEventGroup') is-invalid @enderror">
                @error('registerEndDateForEventGroup')
                    <div class="text-red-600">{{ $message }}</div>
                @enderror
            </label>
        </div>

        <button class="btn-submit mt-6" :disabled="form.processing">
            建立季打
        </button>
    </form>
@endsection
