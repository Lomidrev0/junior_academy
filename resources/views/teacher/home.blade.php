@extends('layouts.main')

@section('authContent')
    <div>
        <div class="mb-5">
            <h1>daco</h1>
            <hr>
            <p>teacher teacher</p>
            <p>{{ Session::get('selected-course')->name }}</p>
        </div>
        <div class="container">
        </div>
    </div>
@endsection