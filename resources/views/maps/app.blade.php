<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="tenant-id" content="{{ tenant('id') }}">
    @auth
    <meta name="user-id" content="{{ Auth::user()->id }}">
    @endauth

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="shortcut icon" href="{{ config('app.url') . '/favicon.ico' }}">
    <!-- Scripts -->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    {{-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> --}}
    <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>

<body>
    <div id="app">
        <main class="py-4">
            @yield('content')
        </main>
    </div>
    {{-- <script src="https://code.jquery.com/jquery.js"></script> --}}

    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBHxfujkxnR8SQM4HlSU5N8NzThx_RU5U4&callback=initMap" type="text/javascript"></script>

    <script src="{{ asset('js/app.js') }}" defer></script>

</body>

</html>
