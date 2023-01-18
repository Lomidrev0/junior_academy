@extends('front.main')
@section('frontContent')
        <header class="headerC"  style="background-image: url('{{ asset($course->media[1]->original_url)}}');">
            <div class="overlayC">
                <h1 class="titleC">{{ $course->name }}</h1>
            </div>
        </header>
             <div class="container">
                 <div class="contentC">
                     {!!$course->about !!}
                         <h5>Kontaktná osoba:</h5>
                         @foreach($course->users as $user)
                             <strong class="nameeC">{{$user->name}}:     </strong><a class="emaill" href="mailto:{{$user->email}}">{{$user->email}}</a>
                        @endforeach
                 </div>
            </div>
@endsection
