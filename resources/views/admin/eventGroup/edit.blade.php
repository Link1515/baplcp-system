@extends('layouts.app')

@php
    use Carbon\Carbon;
@endphp

@section('js')
    @vite('resources/js/eventGroup/edit.js')
@endsection

@section('header')
    <h1 class="mb-2 text-5xl text-center">BAPLCP</h1>
    <h2 class="text-2xl text-center">管理後臺 - 季打</h2>
@endsection

@section('content')
    <form x-data="{
        form: $form('put', '{{ route('admin.eventGroups.update', ['eventGroup' => $eventGroup->id]) }}', {
            title: '',
            subTitle: '',
            singlePrice: '',
            memberParticipants: '',
            nonMemberParticipants: '',
    
            canRegisterAllEvent: '',
            registerAllPrice: '',
            registerAllParticipants: '',
            eventGroupRegisterStartAt: '',
            eventGroupRegisterEndAt: '',
        }),
    }" class="grid gap-4">
        @csrf
        <input type="hidden" name="formSubmitted" value="true">
        <label class="flex flex-col gap-1 text-lg">
            標題
            <input id="title" x-model.fill="form.title" type="text" name="title"
                value="{{ old('title') ?? $eventGroup['title'] }}" class="input @error('title') is-invalid @enderror">
            @error('title')
                <div class="text-red-600">{{ $message }}</div>
            @enderror
        </label>
        <label class="flex flex-col gap-1 text-lg">
            副標題
            <input id="subTitle" x-model.fill="form.subTitle" type="text" name="subTitle"
                value="{{ old('subTitle') ?? $eventGroup['sub_title'] }}"
                class="input @error('subTitle') is-invalid @enderror">
            @error('subTitle')
                <div class="text-red-600">{{ $message }}</div>
            @enderror
        </label>
        <label class="flex flex-col gap-1 text-lg">
            單次費用
            <input id="singlePrice" x-model.fill="form.singlePrice" type="number" name="singlePrice"
                value="{{ old('singlePrice') ?? $eventGroup['price'] }}"
                class="input @error('singlePrice') is-invalid @enderror">
            @error('singlePrice')
                <div class="text-red-600">{{ $message }}</div>
            @enderror
        </label>
        <label class="flex flex-col gap-1 text-lg">
            群內人數
            <input id="memberParticipants" x-model.fill="form.memberParticipants" type="number" name="memberParticipants"
                value="{{ old('memberParticipants') ?? $eventGroup['member_participants'] }}"
                class="input @error('memberParticipants') is-invalid @enderror">
            @error('memberParticipants')
                <div class="text-red-600">{{ $message }}</div>
            @enderror
        </label>
        <label class="flex flex-col gap-1 text-lg">
            群外人數
            <input id="nonMemberParticipants" x-model.fill="form.nonMemberParticipants" type="number"
                name="nonMemberParticipants"
                value="{{ old('nonMemberParticipants') || $eventGroup['non_member_participants'] }}"
                class="input @error('nonMemberParticipants') is-invalid @enderror">
            @error('nonMemberParticipants')
                <div class="text-red-600">{{ $message }}</div>
            @enderror
        </label>
        <label class="flex items-center gap-2 text-lg">
            <input x-model.fill="form.canRegisterAllEvent" id="canRegisterAllEvent" value="true"
                {{ old('formSubmitted') ? (old('canRegisterAllEvent') ? 'checked' : '') : ($eventGroup['can_register_all_event'] ? 'checked' : '') }}
                type="checkbox" name="canRegisterAllEvent" />
            開放報名季打
        </label>
        <div x-show="form.canRegisterAllEvent" class="flex flex-col gap-4" x-collapse>
            <label class="flex flex-col gap-1 text-lg">
                季打費用
                <input id="registerAllPrice" x-model.fill="form.registerAllPrice" type="number" name="registerAllPrice"
                    value="{{ $eventGroup['register_all_price'] }}"
                    class="input @error('registerAllPrice') is-invalid @enderror">
                @error('registerAllPrice')
                    <div class="text-red-600">{{ $message }}</div>
                @enderror
            </label>
            <label class="flex flex-col gap-1 text-lg">
                季打名額
                <input id="registerAllParticipants" x-model.fill="form.registerAllParticipants" type="number"
                    name="registerAllParticipants" value="{{ $eventGroup['register_all_participants'] }}"
                    class="input @error('registerAllParticipants') is-invalid @enderror">
                @error('registerAllParticipants')
                    <div class="text-red-600">{{ $message }}</div>
                @enderror
            </label>
            <label class="flex flex-col gap-1 text-lg">
                季打開放報名時間
                <input id="eventGroupRegisterStartAt" x-model.fill="form.eventGroupRegisterStartAt" type="text"
                    name="eventGroupRegisterStartAt"
                    value="{{ $eventGroup['register_start_at'] ? Carbon::parse($eventGroup['register_start_at'])->format('Y-m-d H:i') : '' }}"
                    class="input @error('eventGroupRegisterStartAt') is-invalid @enderror">
                @error('eventGroupRegisterStartAt')
                    <div class="text-red-600">{{ $message }}</div>
                @enderror
            </label>
            <label class="flex flex-col gap-1 text-lg">
                季打結束報名時間
                <input id="eventGroupRegisterEndAt" x-model.fill="form.eventGroupRegisterEndAt" type="text"
                    name="eventGroupRegisterEndAt"
                    value="{{ $eventGroup['register_end_at'] ? Carbon::parse($eventGroup['register_end_at'])->format('Y-m-d H:i') : '' }}"
                    class="input @error('eventGroupRegisterEndAt') is-invalid @enderror">
                @error('eventGroupRegisterEndAt')
                    <div class="text-red-600">{{ $message }}</div>
                @enderror
            </label>
        </div>

        <div class="grid grid-cols-2 gap-4 mt-6">
            <button
                @click="deleteGroupEvent('{{ route('admin.eventGroups.destroy', ['eventGroup' => $eventGroup->id]) }}', '{{ route('admin.eventGroups.index') }}')"
                class="btn-submit bg-red-500" type="button">
                刪除季打
            </button>
            <button class="btn-submit" :disabled="form.processing">
                更新季打
            </button>
        </div>
    </form>
@endsection
