@extends('layouts.main')

@section('authContent')
    <div>
        <div class="mb-5">
            <h1>{{__('List of registered')}}</h1>
            <hr>
            <p>{{__('List of registered students')}}</p>
        </div>
        <div class="container">
            <member-list
                @isset($courses)
                :courses="{{$courses}}"
                @endisset
                :admin ="{{ Auth::user()->role == 2 ? 'true' : 'false' }}"
            ></member-list>
        </div>
    </div>
@endsection
