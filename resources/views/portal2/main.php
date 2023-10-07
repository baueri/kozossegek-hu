<!doctype html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, maximum-scale=5, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="/portal2/style.min.css" media="screen">
    @yield('portal2.header')
    <title>@yield('subtitle'){{ site_name() }}</title>
</head>
<body class="{{ !is_prod() ? 'demo' : '' }} {{ is_home() ? 'home' : '' }} {{ $body_class ?? '' }}">
    <script src="/portal2/scripts.min.js"></script>
    @include('portal2.partial.menu')

    @yield('portal2.main')
    {{ $main ?? '' }}
    @include('portal2.partial.footer')
</body>
</html>