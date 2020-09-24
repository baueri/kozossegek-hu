<!doctype html>
<html lang="en">
<head>
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>kozossegek.hu - ADMIN</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/bootstrap-margins-paddings.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/assets/sidebar-09/css/style.css">

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link rel="stylesheet" href="/css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    @yield('header')
</head>
<body>
<div class="wrapper d-flex align-items-stretch">
    <nav id="sidebar" style="width: 250px; max-width: 250px; min-width: 250px">
        <div class="img bg-wrap text-center py-4" style="background-image: url(/assets/sidebar-09/images/bg_1.jpg);">
            <div class="user-logo">
<!--                <div class="img" style="background-image: url(/images/logo_only.png);"></div>-->
                <h3 style="">kozossegek.hu | <b>admin</b></h3>
            </div>
        </div>
        @include('admin.partials.menu')
        <ul class="list-unstyled components" style="position:absolute; bottom: 0; width: 100%">
            <li><a href="{{ route('home') }}" target="_blank">oldal megtekintése</a></li>
        </ul>
    </nav>

    <!-- Page Content  -->
    <div id="content" class="p-4 p-md-5 pt-1">
        <h2 class="mb-4">@yield('title')</h2>
        @yield('admin')
    </div>
</div>
@yield('footer')
</body>
</html>
