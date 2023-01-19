@component('mail::message')
    # {{ __('Your account registration has been successful.') }}

    {{ __('login:') }}{{ $data['email'] }}

    {{ __('password:') }}{{ $data['password'] }}

    <a href="{{ url('/') }}" target="_blank">{{ __('Back to the  page') }}</a>


    {{ __('Best Regards,') }}<br>
    {{ config('app.name') }}
@endcomponent
