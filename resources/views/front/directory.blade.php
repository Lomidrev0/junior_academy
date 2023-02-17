@extends('front.main')
@section('frontContent')
    <div class="container">
        <div class="special-height d-flex align-items-center w-100">
            <image-wrapper
                    @isset($dir)
                    :dir="{{$dir}}"
                    @endisset
                    :editable="false"
            ></image-wrapper>
        </div>
    </div>
@endsection
