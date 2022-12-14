@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="vh-mh-100">
        <nav class="navbar navbar-expand-md navbar-light z-index-4 shadow">
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
                    @if(Auth::user()->role == 2)
                        <i class="bi bi-shield-shaded p-3"></i>
                    @elseif(Auth::user()->role == 1)
                        <i class="bi bi-book-half" p-3></i>
                    @else
                        <i class="bi bi-person-fill p-3"></i>
                    @endif
                </div>
            </div>
        </nav>
        <div id="admin-container" class="d-flex vh-mh-100 flex-row">
            <div class=" z-index-3">
                <nav id="sidebar" class="shadow">
                    @if(Auth::user()->role == 2)
                        @include('admin.sidenav')
                    @elseif(Auth::user()->role == 1)
                        @include('teacher.sidenav' , ['courses' => Auth::user()->courses])
                    @else
                        @include('student.sidenav')
                    @endif
                </nav>
            </div>
            <div class="w-100 d-sm-grid content">
                <div class="content-margin">
                    @yield('authContent')
                </div>
                <div class="d-grid align-self-end">
                    @include('shared/footer')
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/admin.js') }}"></script>
@endsection

