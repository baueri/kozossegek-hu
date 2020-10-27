@extends('portal')
    <!--<div class="bg_image"></div>-->
    <div class="container inner login-page">
        <div class="form">
            <form class="login-form" method="post">
                <div class="row">
                    <div class="col-md-4">
                        <h1 class="h4">Belépés</h1>
                    @include('admin.partials.message')
                    <div class="form-group">
                        <input type="text" name="username" placeholder="felhasználónév" autofocus class="form-control"/>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="jelszó" class="form-control"/>
                    </div>
                    <button type="submit" class="btn btn-success">belépés</button>
                    <p class="message mt-3">
                        <a href="/" style="float: left">Vissza a főoldalra</a>
                        <a href="#" style="float: right">Elfelejtettem a jelszavam</a>
                    </p>
                </div>
                </div>
            </form>
        </div>
    </div>
