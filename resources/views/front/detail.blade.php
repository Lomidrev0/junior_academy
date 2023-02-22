@extends('front.main')
@section('frontContent')
        <div class="header header-h-70"  style="background-image: url('{{ asset($course->media[1]->original_url)}}');">
            <div class="overlayC">
                <h1 class="titleC">{{ $course->name }}</h1>
            </div>
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
                             <strong>{{$user->name}}:     </strong><a class="emaill" href="mailto:{{$user->email}}">{{$user->email}}</a>
                         @endforeach
                     </div>
                 </div>
            </div>
@endsection
