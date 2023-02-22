@extends('layouts.main')

@section('authContent')
    <div>
        <div class="mb-5">
            <h1>{{ __('Add user') }}</h1>
            <hr>
            <info-card
                    :text="'{{__('You can add another user here')}}'"
            ></info-card>
        </div>
        <div class="container">
            <add-user
                @isset($courses)
                   :courses="{{$courses}}"
                @endisset
            ></add-user>
        </div>
    </div>
@endsection