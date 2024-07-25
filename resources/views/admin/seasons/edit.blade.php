@extends('layouts.app')

@php
    use Illuminate\Support\Carbon;

    function defaultValue($table, $formField, $tableColumn = null)
    {
        if (is_null($tableColumn)) {
            $tableColumn = $formField;
        }
        return old('formSubmitted') ? old($formField) : $table->$tableColumn;
    }

    $canRegisterAllEventsDefaultValule = old('formSubmitted')
        ? (old('canRegisterAllEvents')
            ? 'checked'
            : '')
        : ($season->can_register_all_events
            ? 'checked'
            : '');

    $seasonRegisterStartAtDefaultValue = $season->register_start_at
        ? $season->register_start_at
        : old('seasonRegisterStartAt');

    $seasonRegisterEndAtDefaultValue = $season->register_end_at ? $season->register_end_at : old('seasonRegisterEndAt');

    $deleteGroupEventMethod = sprintf(
        "requestDelete('是否確定刪除季打','%s', '%s')",
        route('admin.seasons.destroy', ['season' => $season->id]),
        route('admin.seasons.index'),
    );
@endphp

@section('js')
    @vite('resources/js/season/edit.js')
@endsection

@section('header-back-url', route('admin.seasons.options', ['season' => $season->id]))
@section('header')
    <h1 class="mb-2 text-5xl text-center">BAPLCP</h1>
    <h2 class="text-2xl text-center">管理後臺 - 季打</h2>
@endsection

@section('content')
    <form x-data="{
        form: $form('put', '{{ route('admin.seasons.update', ['season' => $season->id]) }}', {
            title: '',
            place: '',
            singlePrice: '',
            totalParticipants: '',
            nonMemberParticipants: '',
    
            canRegisterAllEvents: '',
            registerAllPrice: '',
            registerAllParticipants: '',
            seasonRegisterStartAt: '',
            seasonRegisterEndAt: '',
        }),
    }" class="grid gap-4">
        @csrf
        <input type="hidden" name="formSubmitted" value="true">
        <x-forms.input field="title" :defaultValue="defaultValue(table: $season, formField: 'title')" type="text">
            標題
        </x-forms.input>
        <x-forms.input field="place" :defaultValue="defaultValue(table: $season, formField: 'place')" type="text">
            地點
        </x-forms.input>
        <x-forms.input field="singlePrice" :defaultValue="defaultValue(table: $season, formField: 'singlePrice', tableColumn: 'price')" type="number">
            單次費用
        </x-forms.input>
        <x-forms.input field="totalParticipants" :defaultValue="defaultValue(table: $season, formField: 'totalParticipants', tableColumn: 'total_participants')" type="number">
            總人數
        </x-forms.input>
        <x-forms.input field="nonMemberParticipants" :defaultValue="defaultValue(
            table: $season,
            formField: 'nonMemberParticipants',
            tableColumn: 'non_member_participants',
        )" type="number">
            群外人數
        </x-forms.input>

        <label class="flex items-center gap-2 text-lg">
            <input x-model.fill="form.canRegisterAllEvents" id="canRegisterAllEvents" value="true"
                {{ $canRegisterAllEventsDefaultValule }} type="checkbox" name="canRegisterAllEvents" />
            開放報名季打
        </label>

        <div x-show="form.canRegisterAllEvents" class="flex flex-col gap-4" x-collapse>
            <x-forms.input field="registerAllPrice" :defaultValue="defaultValue(table: $season, formField: 'registerAllPrice', tableColumn: 'register_all_price')" type="number">
                季打費用
            </x-forms.input>
            <x-forms.input field="registerAllParticipants" :defaultValue="defaultValue(
                table: $season,
                formField: 'registerAllParticipants',
                tableColumn: 'register_all_participants',
            )" type="number">
                季打名額
            </x-forms.input>
            <x-forms.input field="seasonRegisterStartAt" :defaultValue="$seasonRegisterStartAtDefaultValue" type="text">
                季打開放報名時間
            </x-forms.input>
            <x-forms.input field="seasonRegisterEndAt" :defaultValue="$seasonRegisterEndAtDefaultValue" type="text">
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
