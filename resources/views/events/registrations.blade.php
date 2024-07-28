@extends('layouts.appNew')

@section('header-back-url', route('events.index'))

@section('content')
    <div x-data="{ tab: 'member' }">
        <div class="h-80 p-4 pb-10 bg-cover" style="background-image: url('{{ asset('images/background/ranking.jpg') }}')">
            <div
                class="w-52 bg-opacity-30 grid h-10 grid-cols-2 p-1 mx-auto mb-8 font-semibold text-center bg-white rounded-full">
                <button @click="tab = 'member'" class="text-white transition-colors rounded-full"
                    :class="tab === 'member' && '!bg-white !text-primary'">群內榜</button>
                <button @click="tab = 'nonMember'" class="text-white transition-colors rounded-full"
                    :class="tab === 'nonMember' && '!bg-white !text-primary'">群外榜</button>
            </div>

            <div class=" grid grid-cols-3 gap-5">
                <div class="relative px-2 mt-8">
                    <div
                        class="absolute bg-[#A0AEBC] w-7 h-7 top-1 right-1 border border-white rounded-full grid place-items-center text-white">
                        2
                    </div>
                    <div class="aspect-square bg-slate-300 mb-3 rounded-full shadow-[5px_8.5px_27.5px_0_#10184080]"></div>
                    <h3 class=" line-clamp-1 font-semibold text-center text-white">User 2</h3>
                </div>
                <div class="relative">
                    <div
                        class="absolute bg-[#DFA64D] w-7 h-7 top-1 right-1 border border-white rounded-full grid place-items-center text-white">
                        1
                    </div>
                    <div class="aspect-square bg-slate-300 mb-3 rounded-full shadow-[5px_8.5px_27.5px_0_#10184080]"></div>
                    <h3 class=" line-clamp-1 font-semibold text-center text-white">User 1</h3>
                </div>
                <div class="relative px-2 mt-8">
                    <div
                        class="absolute bg-[#BF9055] w-6 h-6 top-1 right-1 border border-white rounded-full grid place-items-center text-white">
                        3
                    </div>
                    <div class="aspect-square bg-slate-300 mb-3 rounded-full shadow-[5px_8.5px_27.5px_0_#10184080]"></div>
                    <h3 class=" line-clamp-1 font-semibold text-center text-white">User 3</h3>
                </div>
            </div>
        </div>

        <div class="-mt-10 py-5 px-4 bg-white rounded-[30px]">
            <ol class="overflow-auto text-[15px] grid gap-3" style="height: calc(100dvh - 368px)">
                @foreach (range(4, 10) as $index)
                    @if ($index < 10)
                        <li class="flex items-center gap-4">
                            <span class="text-primary w-6 font-bold text-center">{{ $index }}</span>
                            <div class="bg-slate-300 w-10 h-10 rounded-full"></div>
                            <span class="font-semibold">User {{ $index }}</span>
                        </li>
                    @else
                        <li class="flex items-center gap-4 font-semibold text-[#696F8C]">
                            <span class="w-6 font-bold text-center">{{ $index }}</span>
                            <div class="bg-slate-300 w-10 h-10 rounded-full"></div>
                            <span class=" mr-auto">User {{ $index }}</span>
                            <span class="px-2 py-1 text-[13px] bg-[#EEEEEE]">候補</span>
                        </li>
                    @endif
                @endforeach
            </ol>
        </div>
    </div>
@endsection
