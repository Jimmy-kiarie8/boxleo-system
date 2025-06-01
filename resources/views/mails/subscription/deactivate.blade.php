@component('mail::message')
# Dear {{ $subscriber->name }}

@if ($subscriber->at_trial)
Your trial period has expired

@else

Your account has been deactivated due to unpaid invoice

@endif

@component('mail::button', ['url' => $url])
Go to payment
@endcomponent

Contact us on support@logixsaas.com for help.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
