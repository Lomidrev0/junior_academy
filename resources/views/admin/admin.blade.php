@extends('layouts.main')

@section('authContent')
    <div>
        <div class="mb-5">
            <h1>{{ __('Add user') }}</h1>
            <hr>
            <p>{{ __('You can add another user here') }}</p>
        </div>
        <div>
            <add-user
                @isset($courses)
                   :courses="{{$courses}}"
                @endisset
            ></add-user>
        </div>
    </div>
@endsection
