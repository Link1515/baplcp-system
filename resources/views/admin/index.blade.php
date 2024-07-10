@extends('layouts.app')

@section('header')
    <h1 class="mb-2 text-5xl text-center">BAPLCP</h1>
    <h2 class="text-2xl text-center">管理後臺</h2>
@endsection

@section('content')
    <div class="grid gap-4">
        <a href="{{ route('eventGroups.index') }}" class="border-neutral-500 py-2 text-center border rounded">季打管理</a>
        <a href="#" class="border-neutral-500 py-2 text-center border rounded">會員管理</a>
    </div>
@endsection
