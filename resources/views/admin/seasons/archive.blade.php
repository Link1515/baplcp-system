@extends('layouts.app')

@section('header-back-url', route('admin.seasons.index'))
@section('header')
    <h1 class="mb-2 text-5xl text-center">BAPLCP</h1>
    <h2 class="text-2xl text-center">管理後臺 - 過往季打</h2>
@endsection

@section('content')
    <div class="grid gap-4">
        @foreach ($seasonsGroupByYear as $year => $seasons)
            <h3 class="text-2xl font-semibold">{{ $year }}</h3>
            @foreach ($seasons as $season)
                <a href="{{ route('admin.seasons.options', ['season' => $season->id]) }}"
                    class="btn">{{ $season->title }}</a>
            @endforeach
        @endforeach
    </div>
@endsection