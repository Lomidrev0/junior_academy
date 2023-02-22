@extends('layouts.main')

@section('authContent')
    <div>
        <div class="mb-5">
            <h1>{{__('Home')}}</h1>
            <hr>
{{--            <info-card--}}
{{--                    :text="'{{__('Welcome to JUNIOR Academy teacher panel.')}}'"--}}
{{--            ></info-card>--}}
        </div>
        <div class="container">
            <home :user="{{ Auth::user() }}"></home>
            <h4>Momentálne spravujete kurz: {{ Session::get('selected-course')->name }}</h4>
        </div>
    </div>
@endsection