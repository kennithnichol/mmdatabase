<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>@yield('title')</title>
        <link rel='stylesheet' href='{{ asset('css/app.css') }}'>
        @livewireStyles
    </head>
    <body>
        <div class='container'>
            @yield('content')
        </div>
        <script src='{{ asset('js/app.js') }}' defer></script>
        @livewireScripts
    </body>
</html>
