@extends('portal')
<div class="container text-center">
    <div id="error-pg">
        <div class="error-pg">
            @if(isset($code))
                <div class="error-pg-404">
                    <h1>{{ $code}}</h1>
                </div>
            @endif

            @if(isset($message))
                <h2>{{ $message }}</h2>
            @endif

            @if(isset($message2))
                <p>{{ $message2 }}</p>
            @endif
            <p><a href="@route('home')">Vissza a főoldalra</a></p>
        </div>
    </div>
</div>
<style>

    .error-pg {
        margin: auto;
        max-width: 767px;
        width: 100%;
        line-height: 1.4;
        text-align: center;
        padding: 15px;
    }

    .error-pg .error-pg-404 {
        position: relative;
        height: 220px;
    }

    .error-pg .error-pg-404 h1 {
        font-family: 'Kanit', sans-serif;
        position: absolute;
        left: 50%;
        top: 50%;
        -webkit-transform: translate(-50%, -50%);
        -ms-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
        font-size: 186px;
        font-weight: 200;
        margin: 0px;
        background: linear-gradient(130deg, #ffa34f, #ff6f68);
        color:transparent;
        -webkit-background-clip: text;
        background-clip: text;
        text-transform: uppercase;
    }

    .error-pg h2 {
        font-family: 'Kanit', sans-serif;
        font-size: 33px;
        font-weight: 200;
        text-transform: uppercase;
        margin-top: 0px;
        margin-bottom: 25px;
        letter-spacing: 3px;
    }


    .error-pg p {
        font-family: 'Kanit', sans-serif;
        font-size: 16px;
        font-weight: 200;
        margin-top: 0px;
        margin-bottom: 25px;
    }


    .error-pg a {
        font-family: 'Kanit', sans-serif;
        color: #ff6f68;
        font-weight: 200;
        text-decoration: none;
        border-bottom: 1px dashed #ff6f68;
        border-radius: 2px;
    }

    .error-pg-social>a {
        display: inline-block;
        height: 40px;
        line-height: 40px;
        width: 40px;
        font-size: 14px;
        color: #ff6f68;
        border: 1px solid #efefef;
        border-radius: 50%;
        margin: 3px;
        -webkit-transition: 0.2s all;
        transition: 0.2s all;
    }
    .error-pg-social>a:hover {
        color: #fff;
        background-color: #ff6f68;
        border-color: #ff6f68;
    }
</style>
