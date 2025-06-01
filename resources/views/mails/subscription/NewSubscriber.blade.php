@component('mail::message')
# Hello {{ $user->name }}


Your account has been created. Your password is:
 {{ $password }}


@component('mail::button', ['url' => $url])
Go to Account
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
