@extends('front.main')
@section('frontContent')
    <div class="container">
        <div class="special-height">
            <div class="breadcrumbs">
                <a href="{{route('front-home')}}"><i class="bi bi-house-fill"></i></a> <i class="bi bi-chevron-right"></i>
                <a href="{{route('gallery')}}">{{ __('Gallery') }}</a> <i class="bi bi-chevron-right"></i>
                <a href="{{route('directory', ['slug' => $dir->slug])}}">{{$dir->name}}</a>
            </div>
            <div class="album-header">
                <h3>{{ $dir->name }}</h3>
                <p>{{ $dir->description }}</p>
                <p>{{__('Created at')}}: {{ \Carbon\Carbon::parse($dir->created_at)->format('d.m.Y') }}</p>
            </div>
            <div class="d-flex align-items-center w-100">
                <image-wrapper
                        @isset($dir)
                        :dir="{{$dir}}"
                        @endisset
                        :editable="false"
                ></image-wrapper>
            </div>
        </div>
    </div>
@endsection
