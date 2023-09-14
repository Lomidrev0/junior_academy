@extends('layouts.app')
@section('styles')
    <link href="{{ asset('css/front.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="">
        @include('front/nav')
        @yield('frontContent')
        @include('shared/footer')
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/front.js') }}"></script>
@endsection




