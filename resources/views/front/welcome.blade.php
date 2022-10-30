<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Junior akademia') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
{{--        <link href="{{ asset('css/fonts.css') }}" rel="stylesheet" />--}}
        <link href="{{ asset('css/front.css') }}" rel="stylesheet" />

        <!-- Scripts -->
        <script>
            window.Laravel = {!! json_encode([
          'appUrl' => config('app.url'),
          'appName' => config('app.name'),
          'appId' => env('APP_ID'),
          'locale' => App::getLocale(),
          'locales' => config('app.locales'),
          'debug' => config('app.debug'),
          'csrfToken' => csrf_token(),
          'userId' => Auth::id(),
          'userName' => Auth::user() ? Auth::user()->name : null,
        ]) !!};
        </script>

    </head>
    <body>
    <div id="app">
        @include('front/nav')
        @yield('content')
        @include('shared/footer')
        <example-component2></example-component2>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
