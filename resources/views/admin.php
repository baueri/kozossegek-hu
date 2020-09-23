<!doctype html>
<html lang="en">
<head>
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>kozossegek.hu - ADMIN</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
    <link rel="stylesheet" href="/assets/sidebar-09/css/style.css">
    <link rel="stylesheet" href="/css/admin.css">
</head>
<body>
<div class="wrapper d-flex align-items-stretch">
    <nav id="sidebar" style="width: 250px; max-width: 250px; min-width: 250px">
        <div class="img bg-wrap text-center py-4" style="background-image: url(/assets/sidebar-09/images/bg_1.jpg);">
            <div class="user-logo">
                <div class="img" style="background-image: url(/images/logo_only.png);"></div>
                <h3>kozossegek.hu</h3>
            </div>
        </div>
        <ul class="list-unstyled components mb-5">
            <li class="active">
                <a href="{{ route('admin.dashboard') }}"><span class="fa fa-home mr-3"></span> Vezérlőpult</a>
            </li>
            <li>
                <a href="{{ route('admin.pages') }}"><span class="fa fa-file mr-3"></span> Oldalak</a>
            </li>
            <li>
                <a href="{{ route('admin.groups') }}"><span class="fa fa-church mr-3"></span> Közösségek</a>
            </li>
            <li>
                <a href="{{ route('admin.users') }}"><span class="fa fa-users mr-3"></span> Felhasználók</a>
            </li>
            <li>
                <a href="{{ route('admin.settings') }}"><span class="fa fa-cog mr-3"></span> Gépház</a>
            </li>
            <li>
                <a href="{{ route('admin.logout') }}" class="text-danger"><span class="fa fa-sign-out-alt mr-3"></span> Kilépés</a>
            </li>
        </ul>
        <ul class="list-unstyled components" style="position:absolute; bottom: 0; width: 100%">
            <li><a href="{{ route('home') }}">oldal megtekintése</a></li>
        </ul>
    </nav>

    <!-- Page Content  -->
    <div id="content" class="p-4 p-md-5 pt-1">
        <h2 class="mb-4">@yield('admin_title')</h2>
        @yield('admin')
    </div>
</div>
</body>
</html>
