<div id="{{{ $modalId }}}" class="login-prompt-backdrop" data-mint-modal="1">
    <div class="login-prompt-modal">
        <button type="button" class="login-prompt-close" data-close-login-modal aria-label="Bezárás">
            <i class="fas fa-times"></i>
        </button>

        <div class="login-prompt-icon">
            <i class="fas fa-{{{ $icon }}}"></i>
        </div>

        <h2 class="login-prompt-title">{{{ $title }}}</h2>
        <?php if (trim((string)($slot ?? '')) !== ''): ?>
        <div class="login-prompt-subtitle">{{ $slot }}</div>
        <?php endif; ?>

        <form class="login-prompt-form" action="{{{ $loginAction }}}" method="post">
            @csrf
            <input type="hidden" name="redirect" value="{{{ $redirect }}}">

            <div class="mb-3">
                <input type="text"
                    name="username"
                    id="{{{ $emailId }}}"
                    autocomplete="username"
                    class="form-control login-prompt-input"
                    placeholder="Email cím">
            </div>
            <div class="mb-3">
                <input type="password"
                    name="password"
                    id="{{{ $passwordId }}}"
                    autocomplete="current-password"
                    class="form-control login-prompt-input"
                    placeholder="Jelszó">
            </div>

            <button type="submit" class="btn btn-orange w-100 rounded-pill py-2">
                <i class="fas fa-sign-in-alt me-1"></i> Belépés
            </button>

            <div class="login-prompt-footer">
                <a href="{{{ $forgotUrl }}}">Elfelejtett jelszó</a>
                <span>·</span>
                <a href="{{{ $registerUrl }}}">Regisztráció</a>
            </div>
        </form>
    </div>
</div>
