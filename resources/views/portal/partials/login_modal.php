<?php $redirect = request()['redirect']; ?>
<div class="form">
    <form class="login-form" method="post" action="@route('login', ['redirect' => $redirect])">
        @csrf()
        <div>
                @include('admin.partials.message')
                <div class="form-group">
                    <input type="text" name="username" placeholder="email cím" autofocus class="form-control"/>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="jelszó" class="form-control"/>
                </div>
                <button type="submit" class="btn btn-darkblue">belépés</button>
                <p class="message mt-3">
                    <a href="@route('portal.forgot_password')">Elfelejtettem a jelszavam</a>
                </p>
        </div>
    </form>
</div>
