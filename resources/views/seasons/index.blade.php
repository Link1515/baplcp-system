@extends('layouts.app')

@section('header-back-url', route('home'))
@section('header')
    <h1 class="mb-2 text-5xl text-center">BAPLCP</h1>
    <h2 class="text-2xl text-center">季打報名</h2>
@endsection

@section('content')
    <div class="grid gap-4">
        @foreach ($seasons as $season)
            <a href="{{ route('seasons.show', ['season' => $season->id]) }}" class="btn">{{ $season->title }}</a>
        @endforeach
    </div>
@endsection
