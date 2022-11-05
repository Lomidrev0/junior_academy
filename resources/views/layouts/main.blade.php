@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="h-100">
        <nav class="navbar navbar-expand-md navbar-light bg-white z-index-4 shaddow">
            <div class="container">
                <div>
                    <button type="button" id="sidebarCollapse" class="btn">
                        <i class="bi bi-list m-0"></i>
                    </button>
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>
                <div>
                    {{ Auth::user()->name }}
                </div>
            </div>
        </nav>
        <div class="d-flex h-100 flex-row">
            <div class=" z-index-3">
                <nav id="sidebar" class="shaddow">
                    @if(Auth::user()->role == 2)
                        @include('admin.sidenav')
                    @elseif(Auth::user()->role == 1)
                        {{--      ucitel     --}}
                    @else
                        @include('student.sidenav')
                    @endif
                </nav>
            </div>
            <div class="w-100 d-sm-grid">
                <div class="container-fluid">
                    <div class="content-margin">
                        @yield('authContent')
                    </div>
                </div>
                <div class="d-grid align-self-end">
                    @include('shared/footer')
                </div>

            </div>
        </div>
    </div>
@endsection

