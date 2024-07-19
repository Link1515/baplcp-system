@extends('layouts.app')


@section('js')
    @vite('resources/js/eventGroup/create.js')
@endsection

@section('header-back-url', route('admin.eventGroups.index'))
@section('header')
    <h1 class="mb-2 text-5xl text-center">BAPLCP</h1>
    <h2 class="text-2xl text-center">管理後臺 - 季打</h2>
@endsection

@section('content')
    <form x-data="{
        form: $form('post', '{{ route('admin.eventGroups.store') }}', {
            title: '',
            subTitle: '',
            singlePrice: '',
            memberParticipants: '',
            nonMemberParticipants: '',
    
            eventTime: '',
            eventDates: '',
            eventStartRegisterDayBefore: '',
            eventStartRegisterDayBeforeTime: '',
            eventEndRegisterDayBefore: '',
            eventEndRegisterDayBeforeTime: '',
    
            canRegisterAllEvent: '',
            registerAllPrice: '',
            registerAllParticipants: '',
            eventGroupRegisterStartAt: '',
            eventGroupRegisterEndAt: '',
        }),
    }" class="grid gap-4">
        @csrf
        <input type="hidden" name="formSubmitted" value="true">
        <x-forms.input field="title" :defaultValue="old('title')" type="text">
            標題
        </x-forms.input>
        <x-forms.input field="subTitle" :defaultValue="old('subTitle')" type="text">
            副標題
        </x-forms.input>
        <x-forms.input field="singlePrice" :defaultValue="old('singlePrice')" type="number">
            單次費用
        </x-forms.input>
        <x-forms.input field="memberParticipants" :defaultValue="old('memberParticipants')" type="number">
            群內人數
        </x-forms.input>
        <x-forms.input field="nonMemberParticipants" :defaultValue="old('nonMemberParticipants')" type="number">
            群外人數
        </x-forms.input>
        <x-forms.input field="eventTime" :defaultValue="old('eventTime')" type="text">
            活動開始時間
        </x-forms.input>
        <x-forms.input field="eventDates" :defaultValue="old('eventDates')" type="text">
            日期 (多選)
        </x-forms.input>
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
                <input id="eventEndRegisterDayBeforeTime" x-model.fill="form.eventEndRegisterDayBeforeTime"
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
            <input x-model.fill="form.canRegisterAllEvent" id="canRegisterAllEvent" value="true"
                {{ old('formSubmitted') ? (old('canRegisterAllEvent') ? 'checked' : '') : 'checked' }} type="checkbox"
                name="canRegisterAllEvent" />
            開放報名季打
        </label>
        <div x-show="form.canRegisterAllEvent" class="flex flex-col gap-4" x-collapse>

            <x-forms.input field="registerAllPrice" :defaultValue="old('registerAllPrice')" type="number">
                季打費用
                <template x-if="form.singlePrice && form.eventDates">
                    <small>建議費用: <span x-text="form.singlePrice"></span>(單次費用) x <span
                            x-text="form.eventDates.split(',').length"></span>(次數) = <span
                            x-text="form.singlePrice * form.eventDates.split(',').length"></span></small>
                </template>
            </x-forms.input>
            <x-forms.input field="registerAllParticipants" :defaultValue="old('registerAllParticipants')" type="number">
                季打名額
            </x-forms.input>
            <x-forms.input field="eventGroupRegisterStartAt" :defaultValue="old('eventGroupRegisterStartAt')" type="text">
                季打開放報名時間
            </x-forms.input>
            <x-forms.input field="eventGroupRegisterEndAt" :defaultValue="old('eventGroupRegisterEndAt')" type="text">
                季打結束報名時間
            </x-forms.input>
        </div>

        <button class="btn-submit mt-6" :disabled="form.processing">
            建立季打
        </button>
    </form>
@endsection
