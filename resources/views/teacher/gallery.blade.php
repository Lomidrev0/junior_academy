@extends('layouts.main')

@section('authContent')
    <div>
        <div class="mb-5">
            <h1>Gallery</h1>
            <hr>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
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
