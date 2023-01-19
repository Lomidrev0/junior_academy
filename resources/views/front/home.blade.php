@extends('front.main')
@section('frontContent')
        <header class="header">
             <div class="overlay">
                <h1 class="title">JUNIOR Akadémia</h1>
                    <h1 class="subtitle">Urob viac pre svoju budúcnosť, nauč sa niečo zaujímavé...</h1>
                         <a class="btn button_first marginn" href="#xddd">Registrácia</a>
             </div>
        </header>
            <div class="card_second card-colorr">
                <p class="text_db"><br>Myšlienka založiť JUNIOR akadémiu SPŠE vznikla ako reakcia na signály z&nbsp; priemyselnej praxe o&nbsp;<strong>nedostatku technicky vzdelaných odborníkov</strong>&nbsp;a&nbsp;tiež na obecný trend mladej generácie uprednostňovať humanitné štúdium, čo&nbsp;<strong>nezaručuje</strong>&nbsp;perspektívne uplatnenie na trhu práce.<br><br>V&nbsp;súčasnosti sa SPŠE snaží zvýšiť záujem o&nbsp;štúdium technických a&nbsp;prírodovedných odborov usporiadaním&nbsp;<a href="http://zssutaze.spse-po.sk/">súťaží pre žiakov ZŠ.</a>&nbsp;Tieto aktivity sa stretávajú s&nbsp;kladnou odozvou, pričom účastníci prejavujú záujem aj o&nbsp;dlhodobejšie aktivity - možno celoročne. Práve preto vznikol projekt s názvom JUNIOR akadémia.<br><br>Akadémia je neformálne voľnočasové vzdelávanie nad rámec povinnej školskej dochádzky.<br><strong>Cieľom</strong>&nbsp;je mládež nielen odborne vzdelávať, ale aj zoznamovať sa&nbsp;s atmosférou stredoškolského štúdia.<br><br><strong>Kurzy budú pozostávať z&nbsp;8 dvojhodinových stretnutí (celkovo 16 hodín) v&nbsp;odpoludňajších hodinách v&nbsp;priestoroch SPŠE pod dozorom pedagógov SPŠE a&nbsp;budú prebiehať v období november&nbsp;2022&nbsp;až jún 2023.</strong>Podmienkou otvorenia kurzu je jeho naplnenie desiatimi záujemcami. Viac informácii o kurze získate pri výbere jednotlivých kurzov. Prihlasovanie sa deje výhradne elektronickou formou. O stave vášho prihlásenia budete priebežne informovaní prostredníctvom mailovej adresy, ktorú ste uviedli pri prihlasovaní.<br><br></p>
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
