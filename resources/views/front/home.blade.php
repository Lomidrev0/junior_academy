@extends('front.main')
@section('frontContent')
    <header class="header">
         <div class="overlay">
            <h1 class="title">JUNIOR Akadémia</h1>
            <h1 class="subtitle">Urob viac pre svoju budúcnosť, nauč sa niečo zaujímavé...</h1>
            <a class="btn button_first marginn" href="/register">Registrácia</a>
         </div>
    </header>
    <div class="card_second card-colorr">
        @isset($article)
            {!! $article->content !!}
        @endisset
    </div>
    <div class="container py-4">
         <h1 class="h1 text-center" id="pageHeaderTitle"></h1>
    </div>

    <div class="container">
        <course-list
           @isset($courses)
           :courses="{{$courses}}"
           @endisset
        ></course-list>
    </div>
@endsection
