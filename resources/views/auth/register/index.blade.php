@extends('layouts.plain')

@section('head')
    <link rel="preload" href="{{ asset('images/background/full.png') }}" as="image">
@endsection

@section('content')
    <div class="h-dvh flex flex-col px-6 py-10 bg-cover"
        style="background-image: url('{{ asset('images/background/full.png') }}')">
        <div class="mb-auto">
            <img class=" w-28" src="{{ asset('images/logo.svg') }}" alt="logo">
        </div>

        <div class="mb-12 text-center text-white">
            <h1 class="mb-1 text-3xl font-semibold">歡迎加入</h1>
            <h3 class="text-lg">申請會員以加入 BAPLCP 報名系統</h3>
        </div>
        <x-register.pagination :steps="3" :current="1" class="mb-4" />
        <div class="mx-auto">
            <button class="w-60 bg-primary rounded-xl place-items-center grid h-12 font-semibold text-white">會員申請</button>
        </div>
    </div>
@endsection
