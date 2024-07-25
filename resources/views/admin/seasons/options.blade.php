@extends('layouts.app')

@section('header-back-url', route('admin.seasons.index'))
@section('header')
    <h1 class="mb-2 text-5xl text-center">BAPLCP</h1>
    <h2 class="text-2xl text-center">管理後臺 - 季打</h2>
@endsection

@section('content')
    <div class="grid gap-4">
        <a href="{{ route('admin.seasons.edit', ['season' => $id]) }}" class="btn">編輯季打</a>
        <a href="#" class="btn">臨打管理清單</a>
    </div>
@endsection
