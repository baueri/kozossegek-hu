<!DOCTYPE HTML>
<html lang="en">
<head>
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name='robots' content='noindex,noarchive' />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ site_name() }} - Adminisztráció</title>

    <link href="https://fonts.googleapis.com/css?family=Montserrat|Work+Sans:400,700|Merriweather|Roboto+Condensed:wght@300;400" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="/assets/sidebar-09/css/style.css">

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    @yield('header')
    <script src="/assets/jquery-ui/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="/assets/jquery-ui/jquery-ui.min.css">
    <link rel="stylesheet" href="/css/admin.css?{{ filemtime('css/admin.css') }}">
    <script src="/js/dialog.js?{{ filemtime('js/dialog.js') }}"></script>
    <script src="/js/admin.js?{{ filemtime('js/admin.js') }}"></script>
    <script src="/assets/sidebar-09/js/main.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.9/css/bootstrap-dialog.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.9/js/bootstrap-dialog.min.js"></script>

</head>
<body class="bg-light @if($show_debugbar)has-debugbar @endif">

<com:App.Admin.Components.TopMenu/>

<div class="wrapper d-flex align-items-stretch">
    <nav id="sidebar">
        <div class="img bg-wrap text-center" style="background-image: url(/assets/sidebar-09/images/bg_1.jpg);">
            <div class="user-logo">
                <h3 style="">kozossegek.hu<br/><b>adminisztráció</b></h3>
            </div>
        </div>
        <com:App.Admin.Components.AdminMenu/>
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
@yield('footer')

<script>
    $(()=>{
        $("[title]").tooltip();
        $("#mobile_menu_toggle").click(function(){
            $("body").toggleClass("sidebar-open");
        });
    });
</script>
{{ debugbar()->render() }}
</body>
</html>
