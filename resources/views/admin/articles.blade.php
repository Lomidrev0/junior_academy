@extends('layouts.main')

@section('authContent')
    <div>
        <div class="mb-4">
            <h1>{{ __('Add user') }}</h1>
            <hr>
            <info-card
               :text="'{{__('You can add another user here')}}'"
            ></info-card>
        </div>
        <div>
            <add-article
               @isset($articles)
                 :articles = "{{$articles}}"
               @endisset
            ></add-article>

        </div>
    </div>
@endsection