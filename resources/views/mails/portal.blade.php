@component('mail::message')
# Hello {{ $seller->name }}

Your Boxleo Courier Merchant portal has been {{ $status }}. 
@if ($status == 'activated')
Use below details to login. You can also download our app on playstore. 
@endif

@if ($status == 'activated')
Email: {{ $seller->email }}

Password: {{ $password }}
@component('mail::button', ['url' => $url])
Go to account
@endcomponent

@component('mail::button', ['url' => 'https://play.google.com/store/apps/details?id=boxleomerchant.logixsaas.app'])
Android App
@endcomponent
@endif


Thanks,<br>
{{ config('app.name') }}
@endcomponent
