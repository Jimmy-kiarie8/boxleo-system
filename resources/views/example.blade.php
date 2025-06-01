<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laravel</title>
    <script src="{{ asset('js/admin.js') }}" defer></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    {{-- <div id="admin">
        <v-app>
        <account-setup />
    </v-app> --}}
    {{-- <my-mail /> --}}
    {{-- <my-paypal /> --}}
<div id="admin">
    <my-example></my-example>

</div>

</div>
</body>
</html>
