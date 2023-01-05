@extends('front.main')

@section('frontContent')
    <div class="front-bg">
        <div>
            <h1>JUNIOR Akademia</h1>
            <p>Urob viac pre svoju budúcnosť, nauč sa niečo zaujímavé...</p>
        </div>
    </div>
        <div class="container">
            <div class="row justify-content-center">
                    AKTUALIZÁCIA: V súčasnosti prebieha prihlasovanie záujemcov. Pri naplnení požadovaného počtu 10 záujemcov Vás budeme kontaktovať na mail uvedený pri registrácií a pošleme Vám detailne informácie. Kurzy budeme realizovať prezenčne. Ďakujeme za pochopenie.


                    Myšlienka založiť JUNIOR akadémiu SPŠE vznikla ako reakcia na signály z  priemyselnej praxe o nedostatku technicky vzdelaných odborníkov a tiež na obecný trend mladej generácie uprednostňovať humanitné štúdium, čo nezaručuje perspektívne uplatnenie na trhu práce.

                    V súčasnosti sa SPŠE snaží zvýšiť záujem o štúdium technických a prírodovedných odborov usporiadaním súťaží pre žiakov ZŠ. Tieto aktivity sa stretávajú s kladnou odozvou, pričom účastníci prejavujú záujem aj o dlhodobejšie aktivity - možno celoročne. Práve preto vznikol projekt s názvom JUNIOR akadémia.

                    Akadémia je neformálne voľnočasové vzdelávanie nad rámec povinnej školskej dochádzky.
                    Cieľom je mládež nielen odborne vzdelávať, ale aj zoznamovať sa s atmosférou stredoškolského štúdia.

                    Kurzy budú pozostávať z 8 dvojhodinových stretnutí (celkovo 16 hodín) v odpoludňajších hodinách v priestoroch SPŠE pod dozorom pedagógov SPŠE a budú prebiehať v období november 2022 až jún 2023.

                    Podmienkou otvorenia kurzu je jeho naplnenie desiatimi záujemcami. Viac informácii o kurze získate pri výbere jednotlivých kurzov. Prihlasovanie sa deje výhradne elektronickou formou. O stave vášho prihlásenia budete priebežne informovaní prostredníctvom mailovej adresy, ktorú ste uviedli pri prihlasovaní.
                <div>
                   <course-list
                           @isset($courses)
                           :courses="{{$courses}}"
                           @endisset
                   ></course-list>
                </div>
            </div>
        </div>


@endsection