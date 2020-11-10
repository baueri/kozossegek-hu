<!DOCTYPE html>
<html lang="hu">
    <head>
        <meta charset="utf-8">
        <title></title>
        <style>
            body {
                color: #555;
                font-family: sans-serif;
                font-size: 14px;
                line-height: 2;
            }
        </style>
    </head>
    <body>
        <div style="text-align: center"><img src="https://kozossegek.hu/images/logo_lg.png" style="max-width: 200px;"></div>
        @yield('mail.wrapper')
        @include('mail.footer')
    </body>
</html>
