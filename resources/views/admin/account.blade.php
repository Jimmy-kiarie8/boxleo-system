@extends('layouts.admin')

@section('content')
<v-app>

</v-app>
@endsection


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Colorlib Templates">
    <meta name="author" content="Colorlib">
    <meta name="keywords" content="Colorlib Templates">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" href="{{ asset('css/signup/style.css') }}">
    <link
        href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="{{ asset('css/admin/all.css') }}" rel="stylesheet">
    <script src="{{ asset('js/admin.js') }}" defer></script>
</head>

<body>
    <v-app id="admin">
        <account-setup />
        {{-- <my-account plan="{{ request()->get('plan') }}" /> --}}
    </v-app>

</body>

</html>