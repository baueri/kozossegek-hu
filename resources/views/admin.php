<!doctype html>
<html lang="en">
<head>
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>kozossegek.hu - ADMIN</title>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<!--    <link rel="stylesheet" href="/css/bootstrap-margins-paddings.css">-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
    <link rel="stylesheet" href="/assets/sidebar-09/css/style.css">

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    @yield('header')
    <link rel="stylesheet" href="/css/admin.css">
</head>
<body>
<div class="wrapper d-flex align-items-stretch">
    <nav id="sidebar">
        <div class="img bg-wrap text-center py-4" style="background-image: url(/assets/sidebar-09/images/bg_1.jpg);">
            <div class="user-logo">
<!--                <div class="img" style="background-image: url(/images/logo_only.png);"></div>-->
                <h3 style="">kozossegek.hu | <b>admin</b></h3>
            </div>
        </div>
        @include('admin.partials.menu')
    </nav>

    <!-- Page Content  -->
    <div id="content" class="p-4">
        <h3 class="mb-2">@yield('title')</h3>
        @include('admin.partials.message')
        @yield('admin')
    </div>
</div>
@yield('footer')
</body>
</html>