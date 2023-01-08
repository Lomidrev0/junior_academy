@extends('layouts.main')

@section('authContent')
    <div>
        <div class="mb-5">
            <h1>{{ __('Add user') }}</h1>
            <hr>
            <div class="card">
                <div class="card-body karticka">
            <p class="text">{{ __('You can add another user here') }}</p>
        </div>
    </div>
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
