@component('mail::message')
    # {{ __('Your account registration has been successful.') }}

    {{ __('login:') }}{{ $data['email'] }}

    {{ __('password:') }}{{ $data['password'] }}
    {{ config('app.name') }}
@endcomponent
