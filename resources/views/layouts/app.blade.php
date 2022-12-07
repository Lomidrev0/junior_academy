<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    @yield('styles')
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">

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
        @yield('content')

{{--        <div class="loader-wrapper">--}}
{{--            <div class="loader">--}}
{{--                <div class="bar1"></div>--}}
{{--                <div class="bar2"></div>--}}
{{--                <div class="bar3"></div>--}}
{{--                <div class="bar4"></div>--}}
{{--                <div class="bar5"></div>--}}
{{--                <div class="bar6"></div>--}}
{{--            </div>--}}
{{--        </div>--}}


    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')
    <script>
      //preloader
      $(window).on("load", function () {
        $("div.loader-wrapper").fadeOut(100);
      });
    </script>
</body>
</html>
