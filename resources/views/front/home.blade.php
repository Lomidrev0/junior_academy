@extends('front.main')
@section('frontContent')
    <div class="header">
         <div class="overlay">
             <div class="parallax-content d-flex flex-column">
                 <h1 class="title m-auto">JUNIOR Akadémia</h1>
                 <h1 class="subtitle m-auto my-2">Urob viac pre svoju budúcnosť, nauč sa niečo zaujímavé...</h1>
                 <a class="btn button_first marginn m-auto my-2" href="{{route('register')}}">Registrácia</a>
             </div>
         </div>
    </div>
    <div class="card_second card-colorr">
        @isset($article)
            {!! json_decode($article->value)->text !!}
        @endisset
    </div>
    @if(count($courses) > 0)
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 260">
            <path fill="#ffffff" fill-opacity="1" d="M0,128L80,122.7C160,117,320,107,480,128C640,149,800,203,960,213.3C1120,224,1280,192,1360,176L1440,160L1440,0L1360,0C1280,0,1120,0,960,0C800,0,640,0,480,0C320,0,160,0,80,0L0,0Z"></path>
        </svg>
    <div id="course-list" class="course-wrapper-border">
        <h2 data-aos="zoom-in">Prehľad naších kurzov:</h2>
        <div class="container">
            <course-list
                    @isset($courses)
                    :courses="{{$courses}}"
                    @endisset
            ></course-list>
        </div>
    </div>
    @endif
@endsection
