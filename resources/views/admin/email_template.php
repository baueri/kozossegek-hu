<!doctype html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email minta</title>
    <style>
        body {
            background: #fafafa;
        }
        body > div {
            max-width: 880px;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 40px;
            margin: auto;
            overflow: hidden;
            background: #fff;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
<h1 style="max-width: 880px; margin: auto;">
    {{ $title }}
</h1>
<div>
    {{ $mailable->getBody() }}
</div>
<p style="max-width: 880px; margin: auto;">
    <a href="@route('admin.email_template.list')" style="color: #888;">vissza a sablonokhoz</a>
</p>
</body>
</html>