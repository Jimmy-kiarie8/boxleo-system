@component('mail::message')
# Waybills

Downloaded at: {{ $current_time }}

@component('mail::button', ['url' => $url])
Download Waybills
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
