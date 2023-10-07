<!doctype html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="/portal2/style.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    @yield('portal2.header')
    <title>@yield('subtitle'){{ site_name() }}</title>
</head>
<body class="{{ !is_prod() ? 'demo' : '' }} {{ is_home() ? 'home' : '' }} {{ $body_class ?? '' }}">
    @include('portal2.partial.menu')

    @yield('portal2.main')
    {{ $main ?? '' }}
    @include('portal2.partial.footer')
</body>
</html>