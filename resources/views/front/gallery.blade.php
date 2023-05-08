@extends('front.main')
@section('frontContent')
    <div class="container">
        <div class="special-height gallery-wrapper">
            @if( count($courses) > 0 )
                @foreach($courses as $course)
                    <div class="g-wrapper-item my-10vh">
                        <div class="a-wrapper-head">
                            <hr class="album-hr">
                            <h3>{{$course->name}}</h3>
                            <hr class="album-hr">
                        </div>
                        <div class="grid-container">
                            @foreach($course->directories as $album)
                                <gallery-card
                                        :album = "{{$album}}"
                                ></gallery-card>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @else
                <div class="special-height d-flex align-items-center">
                    <no-results
                            :header="'{{__('No albums found')}}'"
                            :body="'{{__('No course appears to have a single album yet.')}}'"
                    ></no-results>
                </div>
            @endif
        </div>
    </div>
@endsection