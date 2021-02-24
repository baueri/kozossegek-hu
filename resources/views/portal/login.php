@section('header_content')
    @featuredTitle('Belépés')
@endsection
@extends('portal')
<div class="container inner login-page">
    <div class="form">
        <form class="login-form" method="post">
            <div class="row">
                <div class="col-md-6">
                    @message()
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <input type="text" name="username" placeholder="email vagy felhasználónév" autofocus class="form-control"/>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="jelszó" class="form-control"/>
                    </div>
                    <button type="submit" class="btn btn-darkblue">belépés</button>
                    <p class="message mt-3">
                        <a href="@route('portal.register')" style="float: left"><b>Új fiók létrehozása</b></a>
                        <a href="@route('portal.forgot_password')" style="float: right">Elfelejtettem a jelszavam</a>
                    </p>
                </div>
            </div>
        </form>
    </div>
</div>
