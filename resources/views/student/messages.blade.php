@extends('layouts.main')

@section('authContent')
    <div>
        <div class="mb-5">
            <h1>{{__('Inbox')}}</h1>
            <hr>
            <info-card
                    :text="'{{__('Welcome to JUNIOR Academy teacher panel.')}}'"
            ></info-card>
        </div>
        <div class="container">
            <chat
                :messages="{{ json_encode($messages['items']) }}"
                :last-read="{{ json_encode($last_read) }}"
                :auth="{{Auth::user()->role}}"
                :links="{{json_encode($messages['links'])}}"
            ></chat>
        </div>
    </div>
@endsection
