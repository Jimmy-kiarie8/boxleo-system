@component('mail::message')
# Hello {{ $user->name }}

Your account has been created. Your password is:

@component('mail::button', ['url' => $url])
Click here to Login
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
