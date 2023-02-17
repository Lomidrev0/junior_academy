@extends('front.main')

@section('frontContent')
<div class="d-flex justify-content-around contact-wrapper">
    <div class="contact-map d-flex">
        <section class="d-flex flex-column justify-content-around map-wrapper w-100 shadow">
                <div class="map-header">
                    <h2 class="text-center">Tu nás nájdeš</h2>
                </div>
                <iframe allowfullscreen="" frameborder="0" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2618.0497600100175!2d21.24519721567907!3d48.99061047930061!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x473eed7a0628d825%3A0xcd6fe6ac9cace7f2!2sStredn%C3%A1%20priemyseln%C3%A1%20%C5%A1kola%20elektrotechnick%C3%A1!5e0!3m2!1ssk!2ssk!4v1673824714243!5m2!1ssk!2ssk" width="100%" height="450"></iframe>
            </section>
    </div>
    <div class="contact-text">
        <p>Stredná priemyselná škola elektrotechnická Plzenská 1080 47 Prešov</p>
        <strong>Sekretariát :</strong> &nbsp;<a href="mailto:spse@spse-po.sk">spse@spse-po.sk</a>
        <h3 class="mt-5 mb-3">Kontakt na koordinátorov</h3>
         @foreach ($courses as $course)
             <h5 class="mt-4 mb-2"><strong>{{ $course->name }}</strong></h5>
             @foreach($course->users as $user)
                <div class="px-1">
                     {{$user->name}} - <a class="emaill" href="mailto:{{$user->email}}">{{$user->email}}</a>
                </div>
             @endforeach
         @endforeach
    </div>
</div>
@endsection
