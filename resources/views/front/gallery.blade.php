@extends('front.main')
@section('frontContent')
    <div class="container">
        <div class="special-height my-10vh gallery-wrapper">
            @foreach($courses as $course)
                <div class="g-wrapper-item">
                    <div class="a-wrapper-head">
                        <hr class="album-hr">
                        <h3>{{$course->name}}</h3>
                        <hr class="album-hr">
                    </div>
                    <div class="grid-container">
                        @foreach($course->directories as $album)
                            <a class="text-decoration-none" href="{{ route('directory', ['slug' => $album->slug]) }}">
                                <div class="grid-item shadow">
                                    <div class="album-grid-item">
                                        <div class="album-img">
                                            @if(count($album->media) > 0)
                                                <img src="{{$album->media[0]->original_url}}" alt="">
                                            @else
                                                <img src="/images/default_img.png" alt="">
                                            @endif
                                            <div class="album-count">
                                                <i class="bi bi-images"></i>
                                                <span class="px-2">{{$album->media_count}}</span>
                                            </div>
                                        </div>
                                        <div class="album-text">
                                            <h4>{{$album->name}}</h4>
                                            <p class="mx-2">{{Str::limit($album->description, 220, ' ...')}}</p>
                                        </div>
                                        <div class="my-3 mx-3 album-footer">
                                            <p><a href="{{ route('course-detail',['slug' => $album->course->slug]) }}">{{$album->course->name}}</a></p>
                                            <p>{{__('Created at')}}: {{ \Carbon\Carbon::parse($album->created_at)->format('d.m.Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection