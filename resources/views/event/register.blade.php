@extends('layouts.app')

@php
    use Illuminate\Support\Carbon;
    $eventGroup = $event->eventGroup;
@endphp

@section('header-back-url', route('eventGroups.show', ['eventGroup' => $eventGroup->id]))
@section('header')
    <h1 class="mb-2 text-5xl text-center">{{ $eventGroup->title }}</h1>
    <h2 class="text-2xl text-center">{{ $event->start_at }}</h2>
    @vite('resources/js/event/register.js')
@endsection

@section('content')
    <div class="mb-4 text-lg">
        <h3 class="border-neutral-400 pb-2 mb-2 text-xl text-center border-b">活動資訊</h3>
        <div>
            <span class="font-bold">報名開始時間</span>
            <span id="registerStartAt">{{ $event->register_start_at }}</span>
        </div>
        <div>
            <span class="font-bold">報名截止時間</span>
            <span id="registerEndAt">{{ $event->register_end_at }}</span>
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

    @if (!$userHasRegistered || !$userFriendHasRegistered)
        <div class="mb-8 text-lg">
            <h3 class="border-neutral-400 pb-2 mb-2 text-xl text-center border-b">報名活動</h3>
            <form class="grid gap-2" x-data="{
                form: $form('post', '{{ route('eventRegistrations.store') }}', { memberRegister: '', nonMemberRegister: '', nonMemberName: '' })
            }">
                @csrf
                <input type="hidden" name="formSubmitted" value="true">
                <input type="hidden" name="eventId" value="{{ $event->id }}">
                @if (!$userHasRegistered)
                    <label class="cursor-pointer select-none">
                        <input x-model.fill="form.memberRegister" type="checkbox" name="memberRegister" value="true"
                            {{ old('formSubmitted') ? (old('memberRegister') ? 'checked' : '') : '' }} />
                        群內 +1
                    </label>
                @endif
                @if (!$userFriendHasRegistered)
                    <label class="cursor-pointer select-none">
                        <input x-model.fill="form.nonMemberRegister" type="checkbox" name="nonMemberRegister" value="true"
                            {{ old('formSubmitted') ? (old('nonMemberRegister') ? 'checked' : '') : '' }} />
                        群外 +1
                    </label>
                    <div x-show="form.nonMemberRegister" x-collapse style="display: none" x-init="$el.style.display = ''">
                        <x-forms.input field="nonMemberName" :defaultValue="old('nonMemberName')" type="text">
                            群外朋友姓名
                        </x-forms.input>
                    </div>
                @endif

                <span id="submitBtnPlaceholder"
                    class=" h-11 bg-neutral-500 grid items-center mt-6 text-center text-white rounded select-none">
                </span>
                <button id="submitBtn" class="btn-submit mt-6 transition-colors" style="display: none"
                    :disabled="form.processing || (!form.memberRegister && !form.nonMemberRegister)"
                    :class="(!form.memberRegister && !form.nonMemberRegister) && 'bg-neutral-500'">
                    立即報名
                </button>
            </form>
        </div>
    @endif

    @if ($userHasRegistered || $userFriendHasRegistered)
        <div class="mb-8 text-lg">
            <h3 class="border-neutral-400 pb-2 mb-2 text-xl text-center border-b">報名狀態</h3>
            @if ($userHasRegistered)
                <div>
                    已於
                    {{ $userRegistration->updated_at }}
                    報名活動，群內排序為
                    {{ $userRegistration->registration_rank }}
                </div>
            @endif
            @if ($userFriendHasRegistered)
                <div>
                    已於
                    {{ $userFriendRegistration->updated_at }}
                    幫 {{ $userFriendRegistration->non_member_name }}
                    報名活動，群外排序為
                    {{ $userRegistration->registration_rank }}
                </div>
            @endif
        </div>
    @endif

    <div id="registraionList" class="mb-4 text-lg" style="display: none">
        <h3 class="border-neutral-400 pb-2 mb-4 text-xl text-center border-b">報名清單</h3>
        <h4 class="inline-block px-2 py-1 mb-2 text-white bg-blue-600 rounded-full">群內</h4>
        <ol class=" pl-6 mb-6 list-decimal">
            @foreach ($memberRegistrations as $memberRegistration)
                <li>{{ $memberRegistration->user->name }}</li>
            @endforeach
        </ol>
        <h4 class="inline-block px-2 py-1 mb-2 text-white bg-blue-600 rounded-full">群外</h4>
        <ol class=" pl-6 list-decimal">
            @foreach ($nonMemberRegistrations as $nonMemberRegistration)
                <li>
                    <span>
                        {{ $nonMemberRegistration->non_member_name }}
                    </span>
                    <span>
                        ({{ $nonMemberRegistration->user->name }} 的朋友)
                    </span>
                </li>
            @endforeach
        </ol>
    </div>
@endsection
