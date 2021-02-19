<!DOCTYPE HTML>
<html lang="en">
<head>
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name='robots' content='noindex,noarchive' />

    <title>kozossegek.hu - ADMIN</title>

    <link href="https://fonts.googleapis.com/css?family=Montserrat|Work+Sans:400,700|Merriweather|Roboto+Condensed:wght@300;400" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
    <link rel="stylesheet" href="/assets/sidebar-09/css/style.css">

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.9/css/bootstrap-dialog.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.9/js/bootstrap-dialog.min.js"></script>

    @yield('header')
<!--    <script src="/assets/jquery-ui/jquery-ui.min.js"></script>-->
<!--    <link rel="stylesheet" href="/assets/jquery-ui/jquery-ui.min.css">-->
    <link rel="stylesheet" href="/css/admin.css">
    <script src="/js/dialog.js"></script>
    <script src="/js/admin.js"></script>
    <script src="/assets/sidebar-09/js/main.js"></script>

</head>
<body @if($show_debugbar)class="has-debugbar"@endif>
<nav id="top_menu" class="navbar navbar-expand navbar-dark bg-dark fixed-top">
    <ul class="navbar-nav mr-auto">
        <li class="nav-item" id="mobile_menu_toggle">
            <a class="nav-link"><i class="fa fa-bars"></i></a>
        </li>
        @if(isset($current_menu_item['submenu']))
            @foreach($current_menu_item['submenu'] as $submenuItem)
            <li class="nav-item  {{ $submenuItem['active'] ? 'active' : '' }}">
                <a class="nav-link" href="{{ $submenuItem['uri'] }}"><i class="fa fa-{{ $submenuItem['icon'] }}"></i>
                    <span>{{ $submenuItem['title'] }}</a></span>
            </li>
            <li class="nav-item divider"></li>
            @endforeach
        @else
            <li class="nav-item  {{ $current_menu_item['active'] ? 'active' : '' }}">
                <a class="nav-link" href="{{ $current_menu_item['uri'] }}">
                    <i class="fa fa-{{ $current_menu_item['icon'] }}"></i>
                    <span>{{ $current_menu_item['title'] }}</span></a>
            </li>
        @endif
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item text-white">
            <span>Hello <a href="@route('admin.user.profile')">{{ App\Auth\Auth::user()->keresztnev() }}</a></span>
        </li>
        <li class="divider nav-item"></li>
        <li class="nav-item"><a href="@route('home')" title="ugrás az oldalra" target="_blank" class="text-white nav-link"><i class="fa fa-eye"></i></a></li>
        <li class="nav-item"><a href="@route('logout')" title="kilépés" class="text-danger nav-link"><i class="fa fa-sign-out-alt"></i></a></li>
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
@if($show_debugbar)
    {{ debugbar()->render() }}
@endif
@yield('footer')

<script>
    $(()=>{
        $("[title]").tooltip();
        $("#mobile_menu_toggle").click(function(){
            $("body").toggleClass("sidebar-open");
        });
    });
</script>
</body>
</html>
