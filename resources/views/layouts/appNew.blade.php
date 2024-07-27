<!DOCTYPE html>
<html lang="zh-Hant-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'BAPLCP')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite('resources/css/app.css')
    @yield('css')
</head>

<body>
    <div class="lg:max-w-xl lg:shadow max-w-3xl min-h-screen pt-12 mx-auto pb-[72px] relative">
        <header
            class="flex items-center gap-2 h-12 px-4 shadow-[0_1px_3px_0_rgba(0,0,0,0.05)] absolute top-0 left-0 w-full bg-white">
            <a href="@yield('header-back-url', url()->previous())" class="place-items-center grid w-6 h-6 mr-auto">
                <img src="{{ asset('images/icons/back.svg') }}" alt="back">
            </a>
            @yield('header-items')
            <button class="place-items-center grid w-6 h-6">
                <img src="{{ asset('images/icons/burger.svg') }}" alt="burger">
            </button>
        </header>

        <main>
            @yield('content')
        </main>

        @vite('resources/js/app.js')
        @yield('js')

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                @if (session('error'))
                    window.popup.error({
                        text: '{{ session('error') }}'
                    })
                @endif
            })
        </script>
    </div>
</body>

</html>
