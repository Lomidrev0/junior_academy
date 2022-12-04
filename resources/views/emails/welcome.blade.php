@component('mail::message')
    # {{ __('Your account registration has been successful.') }}

    {{ __('Thank you for signing up, :name!', ['name' => $data['name']]) }}
    {{ __('Your account for web app DHZ Župčany has been successfully created.') }}

    {{ __('Your login email to Admin panel DHZ Župčany: ') }}{{ $data['password'] }}

    <a href="{{ url('/') }}" target="_blank">{{ __('Back to the DHZ page') }}</a>


    {{ __('Best Regards,') }}<br>
    {{ config('app.name') }}
@endcomponent
