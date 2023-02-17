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
            {!! $article->content !!}
        @endisset
    </div>
    @if(count($courses) > 0)
    <div class="course-wrapper-border">
        <h2>Prehľad naších kurzov:</h2>
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
