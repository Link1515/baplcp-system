@extends('layouts.appNew')

@php
    use Illuminate\Support\Carbon;
    $season = $event->season;
@endphp

@section('js')
    @vite('resources/js/event/show.js')

    <script>
        @if (session('success'))
            document.addEventListener('DOMContentLoaded', () =>
                window.alerts.registerSuccessAlert()
            )
        @endif

        function cancelRegistration({
            registrationId,
            name,
            isSeason = false
        }) {
            window.popup.confirm({
                title: isSeason ? '您確定要請假？' : `您確定要幫${name}取消報名？`,
                text: isSeason ? '一旦按下確定請假則會取消季打優先卡位權利，若想再次報名，需重新排隊。' : '一旦按下取消報名則會讓排序後面的人遞補，若想再次報名，需重新排隊。',
                confirmButtonText: isSeason ? '確定請假' : '取消報名',
                callback: async function(result) {
                    if (result.isDenied || result.isDismissed) return;
                    try {
                        const {
                            data
                        } = await window.axios.delete(`/eventRegistrations/${registrationId}`);

                        window.popup.success({
                            title: data.title,
                            text: data.text,
                            callback: () => {
                                window.location.reload();
                            }
                        })
                    } catch (error) {
                        if (error instanceof axios.AxiosError) {
                            const response = error.response
                            window.popup.error({
                                title: response.data.title,
                                text: response.data.text,
                                callback: () => {
                                    window.location.reload();
                                }
                            })
                            return
                        }

                        window.popup.error({
                            title: '伺服器忙線中',
                            text: '伺服器目前忙線中，請稍後重試',
                        })
                    }

                }
            })
        }
    </script>
@endsection

@section('header-back-url', route('events.index'))
@section('header-items')
    <a href="{{ route('events.showRegistrations', ['event' => $event->id]) }}" class="text-primary">本週手速榜</a>
@endsection

@section('content')
    <div class="pb-[72px]">
        <div class="p-4 pb-6 text-[15px]">
            <h2 class="title mb-3 text-xl font-semibold">活動資訊</h2>
            <div class="grid grid-cols-[auto_1fr] gap-x-2 gap-y-1 text-[#696F8C] mb-3">
                <span class="text-nowrap">報名開始時間 :</span>
                <span id="registerStartAt" class="text-[#101840] font-medium"
                    data-datetime="{{ $event->register_start_at }}"></span>
                <span class="text-nowrap">報名截止時間 :</span>
                <span id="registerEndAt" class="text-[#101840] font-medium"
                    data-datetime="{{ $event->register_end_at }}"></span>
                <span>活動時間 :</span>
                <span id="startAt" class="text-[#101840] font-medium" data-datetime="{{ $event->start_at }}"></span>
                <span>活動地點 :</span>
                <span class="text-[#101840] font-medium">{{ $season->place }}</span>
                <span>單次費用 :</span>
                <span class="text-[#101840] font-medium">${{ $season->price }}</span>
            </div>
            <div class="grid h-20 grid-cols-3 gap-3">
                <div class="flex flex-col items-center bg-[#f7f8fe] rounded-xl justify-center">
                    <span class="text-primary mb-1 text-2xl font-semibold">
                        {{ $season->register_all_participants ?? 0 }}
                    </span>
                    <span class="font-medium text-[13px] text-[#696F8C]">本週季打</span>
                </div>
                <div class="flex flex-col items-center bg-[#f7f8fe] rounded-xl justify-center">
                    <span class="text-primary mb-1 text-2xl font-semibold">
                        {{ $season->total_participants - $season->register_all_participants - $season->non_member_participants }}
                    </span>
                    <span class="font-medium text-[13px] text-[#696F8C]">群內人數</span>
                </div>
                <div class="flex flex-col items-center bg-[#f7f8fe] rounded-xl justify-center">
                    <span class="text-primary mb-1 text-2xl font-semibold">{{ $season->non_member_participants }}</span>
                    <span class="font-medium text-[13px] text-[#696F8C]">群內人數</span>
                </div>
            </div>
        </div>

        <div class="bg-[#F6F6F6] h-2"></div>

        <div class="px-4 py-6 text-[15px]">
            <div class="flex mb-3">
                <h2 class="title text-xl font-semibold">報名活動</h2>
                <span class=" text-[13px] py-1 px-2 bg-[#f7f8fe] font-semibold text-primary">可預填</span>
            </div>

            @if ($userHasRegistered)
                <div>
                    @if ($userRegistration['type'] === 'season')
                        <h3 class="flex items-center gap-1 mb-3">
                            <div>
                                <img src="{{ asset('images/icons/check.svg') }}" alt="check">
                            </div>
                            <span class="mr-auto">已報名季打</span>
                            <button x-data
                                @click="cancelRegistration({
                            registrationId: {{ $userRegistration['data']->id }},
                            isSeason: true
                        })"
                                class="text-[#6B6B6B] flex gap-1 items-center">
                                我要請假
                                <img src="{{ asset('images/icons/next.svg') }}" alt="next">
                            </button>
                        </h3>
                    @else
                        <h3 class="flex items-center gap-1 mb-3">
                            <div>
                                <img src="{{ asset('images/icons/check.svg') }}" alt="check">
                            </div>
                            <span class="mr-auto">已成功報名</span>
                            <button x-data
                                @click="cancelRegistration({
                            registrationId: {{ $userRegistration['data']->id }},
                            name: '自己',
                        })"
                                class="text-[#6B6B6B] flex gap-1 items-center">
                                我要取消報名
                                <img src="{{ asset('images/icons/next.svg') }}" alt="next">
                            </button>
                        </h3>
                    @endif
                </div>
            @endif
            @if ($userFriendHasRegistered)
                <h3 class="flex items-center gap-1 mb-3">
                    <div>
                        <img src="{{ asset('images/icons/check.svg') }}" alt="check">
                    </div>
                    <span class="mr-auto">
                        已成功幫{{ $userFriendRegistration->non_member_name }}報名
                    </span>
                    <button x-data
                        @click="cancelRegistration({
                            registrationId: {{ $userFriendRegistration->id }},
                            name: '{{ $userFriendRegistration->non_member_name }}',
                        })"
                        class="text-[#6B6B6B] flex gap-1 items-center">
                        我要取消報名
                        <img src="{{ asset('images/icons/next.svg') }}" alt="next">
                    </button>
                </h3>
            @endif

            <form class="grid gap-3 text-[15px]" x-data="{
                form: $form('post', '{{ route('eventRegistrations.store') }}', { memberRegister: '', nonMemberRegister: '', nonMemberName: '' })
            }">
                @csrf
                <input type="hidden" name="formSubmitted" value="true">
                <input type="hidden" name="eventId" value="{{ $event->id }}">
                @if (!$userHasRegistered)
                    <x-forms.checkbox field="memberRegister" value="true" :checked="old('formSubmitted') ? (old('memberRegister') ? 'checked' : '') : ''">
                        群內 +1
                    </x-forms.checkbox>
                @endif
                @if (!$userFriendHasRegistered)
                    <x-forms.checkbox field="nonMemberRegister" value="true" :checked="old('formSubmitted') ? (old('nonMemberRegister') ? 'checked' : '') : ''">
                        群外 +1
                    </x-forms.checkbox>
                    <div x-show="form.nonMemberRegister" class="ml-6" x-collapse style="display: none"
                        x-init="$el.style.display = ''">
                        <label class="flex flex-col gap-1">
                            <input x-model.fill="form.nonMemberName" @keydown.enter.prevent type="text"
                                name="nonMemberName" value="{{ old('nonMemberName') }}"
                                class="input @error('nonMemberName') is-invalid @enderror" placeholder="群外朋友姓名">
                            @error('nonMemberName')
                                <div class="text-error">{{ $message }}</div>
                            @enderror
                        </label>
                    </div>
                @endif

                <div
                    class="fixed md:absolute z-40 bottom-0 left-0 w-full px-4 py-3 h-[72px] shadow-[0_-1px_3px_0_rgba(194,194,194,0.45)] text-base bg-white">
                    @if ($userHasRegistered && $userFriendHasRegistered)
                        <span class="bg-disabled grid items-center h-12 text-center text-white rounded select-none">
                            已報名
                        </span>
                    @else
                        <span id="submitBtnPlaceholder"
                            class="bg-disabled rounded-2xl grid items-center h-12 text-center text-white select-none">
                        </span>
                        <button id="submitBtn" class="btn-primary transition-colors" style="display: none"
                            :disabled="form.processing || (!form.memberRegister && !form.nonMemberRegister)"
                            :class="(!form.memberRegister && !form.nonMemberRegister) && 'bg-disabled'">
                            立即報名
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- <div id="registraionList" class="mb-4 text-lg" style="display: none">
        <h3 class="border-neutral-400 pb-2 mb-4 text-xl text-center border-b">報名清單</h3>
        <h4 class="inline-block px-2 py-1 mb-2 text-white bg-blue-600 rounded-full">群內</h4>
        <ol class=" pl-6 mb-6 list-decimal">
            @foreach ($memberRegistrations as $memberRegistration)
                <li>{{ $memberRegistration->user->name }}</li>
            @endforeach
        </ol>
        <h4 class="inline-block px-2 py-1 mb-2 text-white bg-blue-600 rounded-full">群外</h4>
        <ol class=" pl-6 mb-6 list-decimal">
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
        <h4 class="inline-block px-2 py-1 mb-2 text-white bg-blue-600 rounded-full">季打</h4>
        <ol class=" pl-6 mb-6 list-decimal">
            @foreach ($seasonRegistrations as $seasonRegistration)
                <li>{{ $seasonRegistration->user->name }}</li>
            @endforeach
        </ol>
    </div> --}}
@endsection
