@extends('warehouse.layouts.app')

@section('content')
<v-app>

{{-- <my-header :user="{{ json_encode($auth_user) }}" :tenant="{{ json_encode($tenant) }}"></my-header> --}}
<transition name="fade">
        <my-app :user="{{ json_encode($auth_user) }}" :tenant="'Boxleo Courier'"></my-app>
        {{-- <router-view :user="{{ json_encode($auth_user) }}"></router-view> --}}
</transition>
</v-app>
@endsection
