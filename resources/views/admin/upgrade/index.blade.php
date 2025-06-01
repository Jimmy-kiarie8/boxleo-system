@extends('admin.landingpage.upgrade')

@section('content')

<div id="admin">
    <v-app>
    <my-paypal :plans="{{ $plans }}" :domain="{{ json_encode($domain) }}" :tenant="{{ $tenant }}" />
</v-app>
</div>
@endsection
