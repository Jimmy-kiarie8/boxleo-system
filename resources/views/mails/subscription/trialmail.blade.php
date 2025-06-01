@component('mail::message')
# Introduction


Your trial is about to expire
Hello,

This is to inform you that the trial period for the Premium Trial you have chosen is about to expire. You have only two more days left. Kindly note that you will be moved to Free Plan in 2 days. You can continue enjoying the features by upgrading here. Please do email us to support@zohopeople.com for any further clarifications on this.

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
