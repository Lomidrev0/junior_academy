@extends('layouts.main')

@section('authContent')
    <div>
        <div class="mb-5">
            <h1>{{ __('Course management') }}</h1>
            <hr>
            <p>{{ __('Here you can add new courses or edit them') }}</p>
        </div>
        <div class="container">
            <course-wrapper
                @isset($courses)
                 :courses="{{$courses}}"
                @endisset
                @isset($users)
                 :users="{{$users}}"
                @endisset
            ></course-wrapper>

        </div>
    </div>
@endsection