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
                :messages="{{ $messages }}"
                :last-read="{{ json_encode($last_read) }}"
                :auth="{{Auth::user()->role}}"
            ></chat>
        </div>
    </div>
@endsection
