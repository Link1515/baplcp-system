@extends('layouts.app')

@section('header')
    <h1 class="mb-2 text-5xl text-center">BAPLCP</h1>
    <h2 class="text-2xl text-center">管理後臺</h2>
@endsection

@section('content')
    <div class="grid gap-4">
        <a href="{{ route('admin.eventGroups.index') }}" class="btn">季打管理</a>
        <a href="#" class="btn">會員管理</a>
    </div>
@endsection
