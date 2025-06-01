@extends('layouts.main')

@section('content')
{{-- Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae, molestiae consectetur ea repudiandae cumque nesciunt nulla id consequatur reprehenderit iusto dolores cum aliquid veniam. Ad consequatur eaque corrupti vero molestias. --}}
{{-- <my-header :user="{{ json_encode($auth_user) }}" :tenant="{{ json_encode($tenant) }}"></my-header> --}}
<transition name="fade">
    <v-app>
        <my-app :user="{{ json_encode($auth_user) }}"></my-app>
        {{-- <my-app :user="{{ json_encode($auth_user) }}" :tenant="{{ json_encode($tenant) }}"></my-app> --}}
    </v-app>
</transition>
@endsection
