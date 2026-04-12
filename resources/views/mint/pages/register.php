<mint-extend path="layout/inner.php" :subtitle="Új fiók létrehozása | " :page-title="Új fiók létrehozása">

    <mint-section name="header">
        @if($captchaEnabled)
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
        @endif
    </mint-section>

    <div class="alert alert-info" role="status">
        Kérjük, hogy csak abban az esetben hozz létre új fiókot, ha közösséget hirdetsz.
    </div>
    <?php echo view('admin.partials.message'); ?>

    <form method="post" action="@route('portal.register')">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3 required">
                    <label class="form-label" for="reg-name">Neved</label>
                    <input type="text" class="form-control" id="reg-name" name="name" value="{{ $name }}" data-describedby="validate_user_name" required>
                    <div id="validate_user_name" class="validate_message"></div>
                </div>
                <div class="mb-3 required">
                    <label class="form-label" for="reg-email">Email címed</label>
                    <input type="email" class="form-control" id="reg-email" name="email" value="{{ $email }}" data-describedby="validate_email" required>
                    <div id="validate_email" class="validate_message"></div>
                </div>
                <div class="mb-3 required">
                    <label class="form-label" for="reg-password">Jelszó <small class="text-muted fw-normal">(min. 8 karakter)</small></label>
                    <input type="password" class="form-control" id="reg-password" name="password" data-describedby="validate_password" required>
                    <div id="validate_password" class="validate_message"></div>
                </div>
                <div class="mb-3 required">
                    <label class="form-label" for="reg-password-again">Jelszó még egyszer</label>
                    <input type="password" class="form-control" id="reg-password-again" name="password_again" data-describedby="validate_password_again" required>
                    <div id="validate_password_again" class="validate_message"></div>
                </div>
                <?php if (social_provider_enabled()): ?>
                    <?php echo view('portal.partials.google-login', ['g_context' => 'signup', 'g_text' => 'signup_with']); ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <mod-honeypot :id="register" />
                <p class="mb-3">
                    <?php echo (new \App\Http\Components\AszfCheckBox())->render(); ?><br>
                </p>
                @if($captchaEnabled)
                    <div class="mb-3">
                        <mod-captcha />
                    </div>
                @endif
                <mod-replay-attack :name="register" />
                <div class="mb-3">
                    <button type="submit" class="btn btn-orange px-4 rounded-pill">Regisztráció</button>
                    <p class="mt-2 mb-0">
                        <a href="@route('login')" id="login-existing-user" class="fw-semibold">
                            <i class="fa fa-key" aria-hidden="true"></i> van már fiókom, belépek
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </form>

</mint-extend>
