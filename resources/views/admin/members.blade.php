@extends('layouts.main')

@section('authContent')
    <div>
        <div class="mb-5">
            <h1>{{__('List of registered')}}</h1>
            <hr>
            <p>{{__('List of registered students')}}</p>
        </div>
        <div>
            <member-list :courses="{{$courses}}"></member-list>
        </div>
    </div>
@endsection
