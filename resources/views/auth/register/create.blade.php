@extends('layouts.app')

@section('header')
    <h1 class="mb-1 text-3xl font-semibold text-center">會員申請</h1>
    <h2 class="text-lg text-center">申請以加入 BAPLCP 報名系統</h2>
@endsection

@section('content')
    <main>
        <label class="flex flex-col gap-3 font-medium">
            請輸入您的真實姓名
            <input type="text" class="input">
        </label>
    </main>
@endsection
