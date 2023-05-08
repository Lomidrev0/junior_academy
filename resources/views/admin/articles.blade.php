@extends('layouts.main')

@section('authContent')
    <div>
        <div class="mb-5">
            <h1>{{ __('Update article') }}</h1>
            <hr>
            <info-card
               :text="'{{__('You can edit article on home front page here')}}'"
            ></info-card>
        </div>
        <div class="container">
            <add-article
               @isset($article)
                 :article = "{{$article}}"
               @endisset
            ></add-article>

        </div>
    </div>
@endsection