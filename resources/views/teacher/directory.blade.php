@extends('layouts.main')

@section('authContent')
    <div>
        <div class="mb-5">
            <h1>Gallery</h1>
            <hr>
            <info-card
                    :text="'{{__('Here you can edit album or add photos to album')}}'"
            ></info-card>
        </div>
        <div class="container">
            <image-wrapper
                    @isset($dir)
                    :dir="{{$dir}}"
                    @endisset
            ></image-wrapper>
        </div>
    </div>
@endsection
