<?php $redirect = request()['redirect']; ?>
<div class="auth-card">
    <form class="login-form auth-form" method="post" action="@route('login', ['redirect' => $redirect])">
        @csrf()
        <div class="mb-3">
            @include('admin.partials.message')
        </div>
        <div class="mb-3">
            <label for="login-modal-email" class="form-label">Email cím</label>
            <input type="text"
                   id="login-modal-email"
                   name="username"
                   autocomplete="username"
                   autofocus
                   class="form-control"
                   placeholder="pelda@email.hu"/>
        </div>
        <div class="mb-3">
            <label for="login-modal-password" class="form-label">Jelszó</label>
            <input type="password"
                   id="login-modal-password"
                   name="password"
                   autocomplete="current-password"
                   class="form-control"
                   placeholder="••••••••"/>
        </div>
        @if(social_provider_enabled())
            <div class="auth-divider auth-divider--compact">vagy</div>
            @include('portal.partials.google-login')
        @endif
        <button type="submit" class="btn btn-orange w-100 rounded-pill py-2 mt-1">Belépés</button>
        <div class="auth-form-footer justify-content-center mt-2 mb-0">
            <a href="@route('portal.forgot_password')">Elfelejtett jelszó</a>
        </div>
    </form>
</div>
