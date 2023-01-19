@extends('layouts.main')

@section('authContent')
   <div>
      <div class="mb-5">
         <h1>{{__('Home')}}</h1>
         <hr>
         <info-card
                 :text="'{{__('Welcome to JUNIOR Academy admin panel.')}}'"
         ></info-card>
      </div>
      <div class="container">
      </div>
   </div>
@endsection
