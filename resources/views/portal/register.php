@extends('portal')
@featuredTitle('Új fiók létrehozása')
<div class="container inner">
    @alert('info')
        Kérjük, hogy csak abban az esetben hozz létre új fiókot, ha közösséget hirdetsz.
    @endalert
    @message()
    <form method="post">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group required">
                    <label>Neved</label>
                    <input type="text" class="form-control" name="name"  value="{{ $name }}" data-describedby="validate_user_name" required>
                    <div id="validate_user_name" class="validate_message"></div>
                </div>
                <div class="form-group required">
                    <label>Email címed</label>
                    <input type="email" class="form-control" name="email" value="{{ $email }}"  data-describedby="validate_email" required>
                    <div id="validate_email" class="validate_message"></div>
                </div>
                <div class="form-group required">
                    <label>Jelszó <small>(min. 8 karakter)</small></label>
                    <input type="password" class="form-control" name="password" data-describedby="validate_password" required>
                    <div id="validate_password" class="validate_message"></div>
                </div>
                <div class="form-group required">
                    <label>Jelszó még egyszer</label>
                    <input type="password" class="form-control" name="password_again" data-describedby="validate_password_again" required>
                    <div id="validate_password_again" class="validate_message"></div>
                </div>
                @include('portal.partials.google-login', ['g_context' => 'signup', 'g_text' => 'signup_with'])
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                @honeypot('register')
                <p>
                    @component('aszf')<br/>
                </p>
                @if($captchaEnabled)
                    <div
                        class="cf-turnstile"
                        data-sitekey="{{ $cloudflareSiteKey }}"
                        data-theme="light"
                        data-size="normal"
                        data-callback="cf_onSuccess"
                        data-error-callback="cf_onError"
                        data-expired-callback="cf_onExpired"
                    ></div>
                    <input type="hidden" name="turnstile_token" />
                    <script>
                        (() => {
                            window.cf_onSuccess = function (token) {
                                $("[name='turnstile_token']").val(token);
                            };

                            window.cf_onError = () => {
                                $("[name='turnstile_token']").val('');
                            };

                            window.cf_onExpired = () => {
                                $("[name='turnstile_token']").val('');
                            };
                        })();
                    </script>
                @endif
                <div class="form-group">
                    <button type="submit" class="btn btn-altblue">Regisztráció</button>
                    <p class="mt-2">
                        <a href="@route('login')" id="login-existing-user" onclick="showLoginModal(); return false;"><b>
                            <i class="fa fa-key"></i> van már fiókom, belépek
                        </b></a>
                    </p>
                </div>
            </div>
        </div>
        @csrf()
    </form>
</div>
