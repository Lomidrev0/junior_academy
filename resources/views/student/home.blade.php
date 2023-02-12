@extends('layouts.main')

@section('authContent')
   <div>
      <div class="mb-5">
         <h1>{{__('Home')}}</h1>
         <hr>
      </div>
      <div class="container">
         <home :user="{{$user}}"></home>
         <div>
            {{$user}}
         </div>
      </div>
   </div>
@endsection
