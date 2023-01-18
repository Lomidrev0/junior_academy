@extends('front.main')

@section('frontContent')
<div class="container d-xxl-flex justify-content-xxl-center align-items-xxl-center"style="margin-top: 10vh;margin-bottom: 10vh">
    <div class="col-xxl-7 mappa">
        <section class="map-clean" style="padding: 0px 0px;">
            <div class="container">
                <div class="intro">
                    <h2 class="text-center">Kde nás nájdeš</h2>
                </div>
            </div><iframe allowfullscreen="" frameborder="0" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2618.0497600100175!2d21.24519721567907!3d48.99061047930061!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x473eed7a0628d825%3A0xcd6fe6ac9cace7f2!2sStredn%C3%A1%20priemyseln%C3%A1%20%C5%A1kola%20elektrotechnick%C3%A1!5e0!3m2!1ssk!2ssk!4v1673824714243!5m2!1ssk!2ssk" width="100%" height="450"></iframe>
        </section>
    </div>
    <div class="col-xxl-1 invisible"></div>
    <div class="col-xxl-5 text_left" >
        <p >
            <br>Stredná priemyselná škola elektrotechnická<br>Plzenská 1<br>080 47 Prešov<br><strong>Sekretariát</strong>
            : &nbsp;<a href="mailto:spse@spse-po.sk">spse@spse-po.sk</a>
                         @foreach ($courses as $course)
                             <h3 class="coursess">{{ $course->name }}:</h3>
                        @foreach($course->users as $user)
                    <div class="name_email">
            <strong class="namee">{{$user->name}}:     </strong><a class="emaill" href="mailto:{{$user->email}}">{{$user->email}}</a>
    </div>
        @endforeach
        @endforeach
    </div>
</div>
@endsection
