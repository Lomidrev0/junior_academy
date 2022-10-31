@extends('layouts.app')
@section('styles')
    <link href="{{ asset('css/front.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div>
        @include('front/nav')
        @yield('frontContent')
        @include('shared/footer')
    </div>
@endsection


