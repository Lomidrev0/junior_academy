@extends('layouts.app')

@section('content')
    <div>
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <div>
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>
                <div>
                    {{ Auth::user()->name }}
                </div>
                <div>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                       document.getElementById('logout-form').submit();">
                        <i class="bi bi-door-open-fill"></i>{{ __('Logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </nav>
        <div class="d-flex" id="admin-nav">
            <admin-nav></admin-nav>
        </div>

    <p>admin</p>
    </div>
@endsection
