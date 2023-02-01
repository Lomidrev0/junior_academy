@extends('layouts.main')

@section('authContent')
    <div>
        <div class="mb-5">
            <h1>spravy</h1>
            <hr>
            <info-card
                    :text="'{{__('Welcome to JUNIOR Academy teacher panel.')}}'"
            ></info-card>
        </div>
        <div class="container">
            <chat
               :messages="{{$messages}}"
            ></chat>
        </div>
    </div>
@endsection
