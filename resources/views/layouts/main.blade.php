@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="vh-mh-100">
        <nav class="navbar navbar-expand-md navbar-light z-index-4 shadow">
            <div class="d-flex">
                <button type="button" id="sidebarCollapse" class="btn">
                    <i class="bi bi-list m-0"></i>
                </button>
                <a class="navbar-brand m-0" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>
            <div class="mx-3 justify-content-center d-none d-sm-flex">
                <div class="mx-2">
                    {{ Auth::user()->name }}
                </div>

                @if(Auth::user()->role == 2)
                    <i class="bi bi-shield-shaded "></i>
                @elseif(Auth::user()->role == 1)
                    <i class="bi bi-book-half" ></i>
                @else
                    <i class="bi bi-person-fill "></i>
                @endif
            </div>
            <div id="digital-clock" class="d-flex flex-column align-items-center">
                <span id="clock-time"></span>
                <span id="clock-date"></span>
            </div>
        </nav>
        <div id="admin-container" class="d-flex vh-mh-100 flex-row">
            <div>
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
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        if ({!! json_encode(session()->has('message')) !!}) {
          resetPasToast({!! json_encode(session()->get('message')) !!});
        }
      });
    </script>
@endsection

