@extends('layouts.main')

@section('authContent')
   <div>
      <div class="mb-5">
         <h1>{{__('Home')}}</h1>
         <hr>
      </div>
      <div class="container">
         <home :user="{{$user}}"></home>
         @if(count($user->courses) > 0)
            <h3 class="text-center mb-5">{{__('This is a list of courses you are registered in:')}}</h3>
            <course-list
                    @isset($user)
                    :courses="{{$user->courses}}"
                    @endisset
            ></course-list>
         @endif
      </div>
   </div>
@endsection
