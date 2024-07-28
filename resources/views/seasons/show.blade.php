@extends('layouts.appNew')

@section('js')
    @vite('resources/js/season/show.js')

    <script>
        @if (session('success'))
            document.addEventListener('DOMContentLoaded', () =>
                window.popup.success({
                    title: '您已報名季打',
                    text: '請注意，若報名人數超過上限，將於報名截止後進行抽籤，請靜候報名結果及繳費通知',
                })
            )
        @endif

        function cancelRegistration(registrationId) {
            window.popup.confirm({
                title: '您確定要取消報名？',
                text: '取消報名後，可於此頁在報名時間內重新報名，謝謝!',
                confirmButtonText: '確定取消',
                callback: async function(result) {
                    if (result.isDenied || result.isDismissed) return;
                    try {
                        const {
                            data
                        } = await window.axios.delete(`/seasonRegistrations/${registrationId}`);

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

@section('header-back-url', route('seasons.index'))

@section('content')
    <div class="pb-[72px]">
        <div class="p-4 pb-6 text-sm">
            <h2 class="title mb-3 text-xl font-semibold">季打資訊</h2>
            <div class="grid grid-cols-[auto_1fr] gap-x-2 gap-y-1 text-[#696F8C] mb-3">
                <span class="text-nowrap">報名開始時間 :</span>
                <span id="registerStartAt" class="text-[#101840] font-medium"
                    data-datetime="{{ $season->register_start_at }}"></span>
                <span class="text-nowrap">報名截止時間 :</span>
                <span id="registerEndAt" class="text-[#101840] font-medium"
                    data-datetime="{{ $season->register_end_at }}"></span>
                <span>季打時間 :</span>
                <span class="text-[#101840] font-medium">{{ $seasonRangeStr }}
                    <span id="startAtTime" data-datetime="{{ $firstEventDate }}"></span>
                </span>
                <span>活動地點 :</span>
                <span class="text-[#101840] font-medium">{{ $season->place }}</span>
                <span>單季費用 :</span>
                <span class="text-[#101840] font-medium">${{ number_format($season->register_all_price) }}</span>
            </div>
            <div class="flex flex-col items-center h-20 bg-[#f7f8fe] rounded-xl justify-center">
                <span class="text-primary mb-1 text-2xl font-semibold">
                    {{ $season->register_all_participants ?? 0 }}
                </span>
                <span class="font-medium text-[13px] text-[#696F8C]">本季季打名額</span>
            </div>
        </div>

        <form class="text-lg" x-data="{
            form: $form('post', '{{ route('seasonRegistrations.store') }}', {})
        }">
            @csrf
            <input type="hidden" name="seasonId" value="{{ $season->id }}">
            <div
                class="fixed md:absolute z-40 bottom-0 left-0 w-full px-4 py-3 h-[72px] shadow-[0_-1px_3px_0_rgba(194,194,194,0.45)] text-base bg-white">
                <span id="submitBtnPlaceholder"
                    class=" bg-disabled rounded-2xl grid items-center h-12 text-center text-white select-none">
                </span>
                @if ($userRegistration)
                    <button id="submitBtn" @click="cancelRegistration({{ $userRegistration->id }})" type="button"
                        class="btn-outline-primary transition-colors" style="display: none">
                        取消報名
                    </button>
                @else
                    <button id="submitBtn" class="btn-primary transition-colors" style="display: none"
                        :disabled="form.processing" :class="form.processing && 'bg-neutral-500'">
                        立即報名
                    </button>
                @endif
            </div>
        </form>

        <div class="bg-[#F6F6F6] h-2"></div>

        <div id="registraionList" class="px-4 py-6" style="display: none">
            <h2 class="title mb-3 text-xl font-semibold">報名清單</h2>

            <ol class="text-[15px]">
                @foreach ($memberRegistrations as $index => $memberRegistration)
                    <li class=" flex items-center gap-3">
                        <span class=" font-bold">{{ $index + 1 }}</span>
                        <div class=" bg-slate-300 w-10 h-10 rounded-full"></div>
                        <span class=" font-medium">{{ $memberRegistration->user->name }}</span>
                    </li>
                @endforeach
            </ol>
        </div>
    </div>
@endsection
