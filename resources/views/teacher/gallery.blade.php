@extends('layouts.main')

@section('authContent')
    <div>
        <div class="mb-5">
            <h1>Gallery</h1>
            <hr>
            <info-card
                    :text="'{{ __('Here you can add new albums or edit them') }}'"
            ></info-card>
        </div>
        <div class="container">
            <album-wrapper
                @isset($albums)
                :albums="{{$albums}}"
                @endisset
            ></album-wrapper>
        </div>
    </div>
@endsection
