<!doctype html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="/portal2/style.css">
    <title>@yield('subtitle'){{ site_name() }}</title>
</head>
<body class="{{ !is_prod() ? 'demo' : '' }} {{ is_home() ? 'home' : '' }} {{ $body_class ?? '' }}">
    @include('portal2.partial.menu')

    @yield('portal2.main')

    @include('portal2.partial.footer')

</body>
</html>