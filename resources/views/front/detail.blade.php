@extends('front.main')
@section('frontContent')
        <div class="course-header header-h-75 position-relative" style="background-image: url('{{ asset($course->media[1]->original_url)}}');">
            <div class="overlayC">
                <h1 class="titleC">{{ $course->name }}</h1>
            </div>
                <svg class="bottom-0 position-absolute" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 280">
                    <path fill="#FAF9F6" fill-opacity="1" d="M0,256L80,261.3C160,267,320,277,480,277.3C640,277,800,267,960,261.3C1120,256,1280,256,1360,256L1440,256L1440,320L1360,320C1280,320,1120,320,960,320C800,320,640,320,480,320C320,320,160,320,80,320L0,320Z"></path>
                </svg>
        </div>
             <div class="container">
                 @if( $course->active == 0)
                     <div class="my-3">
                         <info-card
                                 :text="'{{ __('You are currently viewing an inactive course. It is not possible to register for this course') }}'"
                                 :type="{{json_encode('war')}}"
                         ></info-card>
                     </div>
                 @endif
                 <div class="contentC">
                     {!!$course->about !!}
                     <div class="my-5">
                         <h5>Kontaktná osoba:</h5>
                         @foreach($course->users as $user)
                             <strong>{{$user->name}}:</strong>
                             <a class="emaill" href="mailto:{{$user->email}}">{{$user->email}}</a>
                             @if (!$loop->last)
                                 ,
                             @endif
                         @endforeach
                     </div>
                 </div>
                     @if(count($albums) != 0)
                         <div>
                             <h3 class="text-center">{{ __('Gallery for course:') .' '.$course->name }}</h3>
                             <div class="grid-container my-10vh">
                                 @foreach($albums as $album)
                                     <gallery-card
                                             :album = "{{$album}}"
                                     ></gallery-card>
                                 @endforeach
                             </div>
                         </div>
                     @endif
            </div>
@endsection
