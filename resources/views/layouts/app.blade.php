<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
{{--    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">--}}

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
        <main>
            @yield('content')
        </main>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
