@component('mail::message')
    # {{ __('Registration for courses has been restarted!') }}

    <p style="word-break: break-word;">
        {{ __('Hooray, registration has started again, so do not waste any more time and run to sign up, because we are taking a limited number of students for the course') }}
    </p>
    @if($data['dateTime'])
        {{ __('Registration is launched until:') }}{{$data['dateTime']}}
    @endif

    {{ config('app.name') }}
@endcomponent
