@component('mail::message')
# {{$message->subject}}

{{$message->content}}
<br>
<p style="width: 50rem;">{{$message->sender->name}} - <a href="mailto:{{$message->sender->email}}">{{$message->sender->email}}</a> - {{__('Instructor of course')}}: {{$message->course->name}}</p>

@component('mail::button', ['url' => url()->previous()])
    {{__('Check inbox')}}
@endcomponent

@endcomponent
