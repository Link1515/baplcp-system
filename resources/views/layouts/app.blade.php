<!DOCTYPE html>
<html lang="zh-Hant-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'BAPLCP')</title>
    <link rel="preload" href="{{ asset('images/background/header.png') }}" as="image">

    @vite('resources/css/app.css')
    @yield('css')
</head>

<body>
    <div class="lg:max-w-xl lg:shadow max-w-3xl mx-auto">
        <header class="min-h-48 place-items-center relative grid mb-8 bg-center bg-cover"
            style="background-image: url('{{ asset('images/background/header.png') }}')">
            <div class="lg:p-6 p-4 text-white">
                @if (Route::currentRouteName() !== 'home')
                    <div class="top-4 left-2 absolute flex items-center gap-2">
                        <a href="{{ url()->previous() }}">
                            <img src="{{ asset('images/icons/back.svg') }}" class="w-10" alt="back">
                        </a>
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('images/icons/home.svg') }}" class="w-8" alt="home">
                        </a>
                    </div>
                @endif
                @yield('header')
            </div>
        </header>

        <main class="lg:px-6 px-4 pb-8">
            @yield('content')
        </main>

        @vite('resources/js/app.js')
        @yield('js')
    </div>
</body>

</html>
