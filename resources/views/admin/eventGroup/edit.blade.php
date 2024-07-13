@extends('layouts.app')

@php
    use Carbon\Carbon;

    function defaultValue($table, $formField, $tableColumn = null)
    {
        if (is_null($tableColumn)) {
            $tableColumn = $formField;
        }
        return old('formSubmitted') ? old($formField) : $table[$tableColumn];
    }

    $canRegisterAllEventDefaultValule = old('formSubmitted')
        ? (old('canRegisterAllEvent')
            ? 'checked'
            : '')
        : ($eventGroup['can_register_all_event']
            ? 'checked'
            : '');

    $eventGroupRegisterStartAtDefaultValue = $eventGroup['register_start_at']
        ? Carbon::parse($eventGroup['register_start_at'])->format('Y-m-d H:i')
        : old('eventGroupRegisterStartAt');

    $eventGroupRegisterEndAtDefaultValue = $eventGroup['register_end_at']
        ? Carbon::parse($eventGroup['register_end_at'])->format('Y-m-d H:i')
        : old('eventGroupRegisterEndAt');

    $deleteGroupEventMethod = sprintf(
        "deleteGroupEvent('%s', '%s')",
        route('admin.eventGroups.destroy', ['eventGroup' => $eventGroup->id]),
        route('admin.eventGroups.index'),
    );
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
        <x-forms.input field="title" :defaultValue="defaultValue(table: $eventGroup, formField: 'title')" type="text">
            標題
        </x-forms.input>
        <x-forms.input field="subTitle" :defaultValue="defaultValue(table: $eventGroup, formField: 'subTitle', tableColumn: 'sub_title')" type="text">
            副標題
        </x-forms.input>
        <x-forms.input field="singlePrice" :defaultValue="defaultValue(table: $eventGroup, formField: 'singlePrice', tableColumn: 'price')" type="number">
            單次費用
        </x-forms.input>
        <x-forms.input field="memberParticipants" :defaultValue="defaultValue(
            table: $eventGroup,
            formField: 'memberParticipants',
            tableColumn: 'member_participants',
        )" type="number">
            群內人數
        </x-forms.input>
        <x-forms.input field="nonMemberParticipants" :defaultValue="defaultValue(
            table: $eventGroup,
            formField: 'nonMemberParticipants',
            tableColumn: 'non_member_participants',
        )" type="number">
            群外人數
        </x-forms.input>

        <label class="flex items-center gap-2 text-lg">
            <input x-model.fill="form.canRegisterAllEvent" id="canRegisterAllEvent" value="true"
                {{ $canRegisterAllEventDefaultValule }} type="checkbox" name="canRegisterAllEvent" />
            開放報名季打
        </label>

        <div x-show="form.canRegisterAllEvent" class="flex flex-col gap-4" x-collapse>
            <x-forms.input field="registerAllPrice" :defaultValue="defaultValue(
                table: $eventGroup,
                formField: 'registerAllPrice',
                tableColumn: 'register_all_price',
            )" type="number">
                季打費用
            </x-forms.input>
            <x-forms.input field="registerAllParticipants" :defaultValue="defaultValue(
                table: $eventGroup,
                formField: 'registerAllParticipants',
                tableColumn: 'register_all_participants',
            )" type="number">
                季打名額
            </x-forms.input>
            <x-forms.input field="eventGroupRegisterStartAt" :defaultValue="$eventGroupRegisterStartAtDefaultValue" type="text">
                季打開放報名時間
            </x-forms.input>
            <x-forms.input field="eventGroupRegisterEndAt" :defaultValue="$eventGroupRegisterEndAtDefaultValue" type="text">
                季打結束報名時間
            </x-forms.input>
        </div>

        <div class="grid grid-cols-2 gap-4 mt-6">
            <button @click="{{ $deleteGroupEventMethod }}" class="btn-submit bg-red-500" type="button">
                刪除季打
            </button>
            <button class="btn-submit" :disabled="form.processing">
                更新季打
            </button>
        </div>
    </form>
@endsection
