@extends('layouts.main')

@section('authContent')
   <div>
      <div class="mb-5">
         <h1>{{__('Home')}}</h1>
         <hr>
{{--         <info-card--}}
{{--                 :text="'{{__('Welcome to JUNIOR Academy admin panel.')}}'"--}}
{{--         ></info-card>--}}
      </div>
      <div class="container">
         <home :user="{{ Auth::user() }}"></home>
         <div>
            <h4 class="mt-4">Prehľad počtu zaregistrovaných na kurzy </h4>
            <div class="my-2 px-2">
               @foreach($courses as $course)
                  <div class="p-2">
                     <img class="avatar-img-sm" src="{{$course->media[0]->original_url}}" alt="">
                     {{$course->name}}
                     <i class="bi bi-arrow-right"></i>
                     počet zaregistrovaných:
                     {{$course->users_count}}
                  </div>
               @endforeach
            </div>

            <h4 class="mt-4">Zoznam všetkých inštruktorov</h4>
            <div class="my-2 px-2">
               @foreach($teachers as $teacher)
                  <div>{{$teacher->name}}:  <a href="mailto:{{$teacher->email}}">{{$teacher->email}}</a></div>
               @endforeach
            </div>

            <h4 class="mt-4">Zoznam všetkých administrátorov</h4>
            <div class="my-2 px-2">
               @foreach($admins as $admin)
                  <div>
                     @if($admin->id == Auth::user()->id)
                        <strong>{{$admin->name}}</strong>
                     @else
                        {{$admin->name}}
                     @endif
                  </div>
               @endforeach
            </div>
         </div>
      </div>
   </div>

@endsection