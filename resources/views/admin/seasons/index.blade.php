@extends('layouts.app')

@section('header-back-url', route('admin.index'))
@section('header')
    <h1 class="mb-2 text-5xl text-center">BAPLCP</h1>
    <h2 class="text-2xl text-center">管理後臺 - 季打</h2>
@endsection

@section('content')
    <div class="grid gap-4">
        @foreach ($seasons as $season)
            <a href="{{ route('admin.seasons.edit', ['season' => $season->id]) }}" class="btn">{{ $season->title }}</a>
        @endforeach
        <a href="{{ route('admin.seasons.create') }}" class="btn">+</a>
    </div>
@endsection
