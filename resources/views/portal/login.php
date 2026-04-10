@extends('portal2026.portal')
@featuredTitle('Belépés')
<div class="container inner inner--auth">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-5 col-xl-4">
            <div class="auth-card">
                <form class="login-form auth-form" method="post">
                    @csrf()
                    <div class="mb-4">
                        @message()
                    </div>
                    <div class="mb-3">
                        <label for="login-page-email" class="form-label">Email cím</label>
                        <input type="email"
                               id="login-page-email"
                               name="username"
                               autocomplete="username"
                               autofocus
                               class="form-control form-control-lg"
                               placeholder="pelda@email.hu"/>
                    </div>
                    <div class="mb-3">
                        <label for="login-page-password" class="form-label">Jelszó</label>
                        <input type="password"
                               id="login-page-password"
                               name="password"
                               autocomplete="current-password"
                               class="form-control form-control-lg"
                               placeholder="••••••••"/>
                    </div>
                    @if(social_provider_enabled())
                        <div class="auth-divider">vagy</div>
                        @include('portal.partials.google-login')
                    @endif
                    <button type="submit" class="btn btn-orange w-100 rounded-pill py-2 mt-1">Belépés</button>
                    <div class="auth-form-footer">
                        <a href="@route('portal.register')" class="auth-form-footer__primary">Új fiók létrehozása</a>
                        <a href="@route('portal.forgot_password')">Elfelejtett jelszó</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
