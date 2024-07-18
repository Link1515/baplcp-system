@extends('layouts.app')

@php
    use Carbon\Carbon;
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
            <span>{{ $event->register_start_at }}</span>
        </div>
        <div>
            <span class="font-bold">報名截止時間</span>
            <span>{{ $event->register_end_at }}</span>
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

    @if (!$memberHasRegistered || !$nonMemberHasRegistered)
        <div class="mb-8 text-lg">
            <h3 class="border-neutral-400 pb-2 mb-2 text-xl text-center border-b">報名活動</h3>
            <form class="grid gap-2" x-data="{
                form: $form('post', '{{ route('eventRegistrations.store') }}', { memberRegister: '', nonMemberRegister: '', nonMemberName: '' })
            }">
                <input type="hidden" name="formSubmitted" value="true">
                @csrf
                <input type="hidden" name="eventId" value="{{ $event->id }}">
                @if (!$memberHasRegistered)
                    <label>
                        <input x-model.fill="form.memberRegister" type="checkbox" name="memberRegister" value="true"
                            {{ old('formSubmitted') ? (old('memberRegister') ? 'checked' : '') : '' }} />
                        群內 +1
                    </label>
                @endif
                @if (!$nonMemberHasRegistered)
                    <label>
                        <input x-model.fill="form.nonMemberRegister" type="checkbox" name="nonMemberRegister" value="true"
                            {{ old('formSubmitted') ? (old('nonMemberRegister') ? 'checked' : '') : '' }} />
                        群外 +1
                    </label>
                    <div x-show="form.nonMemberRegister" x-collapse>
                        <x-forms.input field="nonMemberName" :defaultValue="old('nonMemberName')" type="text">
                            群外朋友姓名
                        </x-forms.input>
                    </div>
                @endif

                <button class="btn-submit mt-6 transition-colors"
                    :disabled="!form.memberRegister && !form.nonMemberRegister"
                    :class="(!form.memberRegister && !form.nonMemberRegister) && 'bg-neutral-500'"
                    :disabled="form.processing">
                    立即報名
                </button>
            </form>

        </div>
    @endif

    @if ($memberHasRegistered || $nonMemberHasRegistered)
        <div class="mb-4 text-lg">
            <h3 class="border-neutral-400 pb-2 mb-2 text-xl text-center border-b">報名狀態</h3>
            @if ($memberHasRegistered)
                <div>
                    已於
                    {{ $memberRegistration->updated_at }}
                    報名活動
                </div>
            @endif
            @if ($nonMemberHasRegistered)
                <div>
                    已於
                    {{ $nonMemberRegistration->updated_at }}
                    幫 {{ $nonMemberRegistration['non_member_name'] }}
                    報名活動
                </div>
            @endif
        </div>
    @endif
@endsection
