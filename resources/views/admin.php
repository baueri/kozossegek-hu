<!doctype html>
<html lang="en">
<head>
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>kozossegek.hu - ADMIN</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
    <link rel="stylesheet" href="/assets/sidebar-09/css/style.css">

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <!-- <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script> -->

    @yield('header')
    <link rel="stylesheet" href="/css/admin.css">
    <script src="/js/admin.js"></script>

</head>
<body>
<nav id="top_menu" class="navbar navbar-expand navbar-dark bg-dark fixed-top">
    <ul class="navbar-nav mr-auto">
        @if(isset($current_menu_item['submenu']))
            @foreach($current_menu_item['submenu'] as $submenuItem)
            <li class="nav-item  {{ $submenuItem['active'] ? 'active' : '' }}">
                <a class="nav-link" href="{{ $submenuItem['uri'] }}"><i class="fa fa-{{ $submenuItem['icon'] }}"></i> {{ $submenuItem['title'] }}</a>
            </li>
            <li class="nav-item divider"></li>
            @endforeach
        @else
            <li class="nav-item  {{ $current_menu_item['active'] ? 'active' : '' }}">
                <a class="nav-link" href="{{ $current_menu_item['uri'] }}"><i class="fa fa-{{ $current_menu_item['icon'] }}"></i> {{ $current_menu_item['title'] }}</a>
            </li>
        @endif
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item text-white">
            Hello <a href="@route('admin.user.profile')">{{ App\Auth\Auth::user()->keresztnev() }}</a>
        </li>
        <li class="divider nav-item"></li>
        <li class="nav-item"><a href="@route('home')" title="ugrás az oldalra" target="_blank" class="text-white nav-link"><i class="fa fa-eye"></i></a></li>
        <li class="nav-item"><a href="@route('admin.logout')" title="kilépés" class="text-danger nav-link"><i class="fa fa-sign-out-alt"></i></a></li>
    </ul>
</nav>

<div class="wrapper d-flex align-items-stretch">
    <nav id="sidebar">
        <div class="img bg-wrap text-center" style="background-image: url(/assets/sidebar-09/images/bg_1.jpg);">
            <div class="user-logo">
<!--                <div class="img" style="background-image: url(/images/logo_only.png);"></div>-->
                <h3 style="">kozossegek.hu | <b>admin</b></h3>
            </div>
        </div>
        @include('admin.partials.menu')
    </nav>

    <!-- Page Content  -->
    <div id="content" class="">
        <h5 class="mb-0 pt-2 pb-2 pl-4" id="admin-title">@yield('title')</h5>
        <div class="p-3">
            @include('admin.partials.message')
            @yield('admin')
        </div>

        <footer id="footer" class="p-3 text-right">
            <i>közösségek.hu {{ APP_VERSION }}</i>
        </footer>
    </div>
</div>

@if($is_maintenance_on)
    <div id="is_maintenance_on" title="Karbantartás bekapcsolva!"><i class="fa fa-exclamation-triangle text-danger" style="font-size: 36px; cursor:pointer;"></i></div>
@endif
@yield('footer')
<script>
    $(()=>{
        $("[title]").tooltip();
    });
</script>
</body>
</html>
