<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">
    <!-- Styles -->
    @yield('style')
    <link rel="stylesheet" href="{{ asset(mix('css/app.css')) }}">
    <link rel="shortcut icon" href="{{ asset('images/logo/vads-favicon.ico') }}" />
    <link rel="stylesheet" href="{{ asset('vendors/fontawesome/css/all.min.css') }}">
</head>
@yield('body')

</html>
