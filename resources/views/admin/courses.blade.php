@extends('layouts.main')

@section('authContent')
    <div>
        <div class="mb-5">
            <h1>{{ __('Course management') }}</h1>
            <hr>
            <info-card
                    :text="'{{ __('Here you can add new courses or edit them') }}'"
            ></info-card>
        </div>
        <div class="container">
            <course-wrapper
                @isset($courses)
                 :courses="{{$courses}}"
                @endisset
                @isset($users)
                 :users="{{$users}}"
                @endisset
                @isset($registration)
                :reg="{{json_encode($registration)}}"
                @endisset
            ></course-wrapper>

        </div>
    </div>
@endsection