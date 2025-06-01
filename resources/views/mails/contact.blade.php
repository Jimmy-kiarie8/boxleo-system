@component('mail::message')

@if ($sender)
# Hello {{ $name }}

Thank you for contacting Logixsaas. Our team will contact you through your email as soon as possible.

@else
# Hello, You have a new email from {{ $name }}
Email: {{ $email }}

Phone: {{ $phone }}

{{ $content }}
@endif

Thanks,

{{ config('app.name') }}
@endcomponent
