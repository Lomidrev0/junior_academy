@extends('layouts.main')

@section('authContent')
    <div>
        <div class="mb-5">
            <h1>spravy</h1>
            <hr>
            <info-card
                    :text="'{{__('You can send or view messages here.')}}'"
            ></info-card>
        </div>
        <div class="container">
            <chat
               :messages="{{ $messages }}"
               :last-read="{{ json_encode($last_read) }}"
               :auth="{{Auth::user()->role}}"
               :course="{{Session::get('selected-course')}}"
            ></chat>
        </div>
    </div>
@endsection
