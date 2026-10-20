<mint-extend path="layout/inner.php" :subtitle="Alkalmazás csatlakoztatása | " :page-title="Alkalmazás csatlakoztatása" :inner-class="inner--auth">

    <?php
        $redirectHost = parse_url((string) ($params['redirect_uri'] ?? ''), PHP_URL_HOST);
        $redirectHost = is_string($redirectHost) && $redirectHost !== '' ? $redirectHost : null;
    ?>

    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
            <div class="auth-card">
                <?php if ($redirectHost): ?>
                    <p class="text-muted small mb-1">Csatlakozás ehhez az oldalhoz:</p>
                    <h2 class="h4 mb-2">
                        <strong><code><?php echo htmlspecialchars($redirectHost, ENT_QUOTES, 'UTF-8'); ?></code></strong>
                    </h2>
                    <p class="text-muted small mb-3">
                        <?php echo htmlspecialchars($client->name, ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                    <p class="mb-3">
                        A(z) <strong><code><?php echo htmlspecialchars($redirectHost, ENT_QUOTES, 'UTF-8'); ?></code></strong>
                        oldal a következőket kérné a kozossegek.hu fiókodhoz:
                    </p>
                <?php else: ?>
                    <h2 class="h4 mb-3"><?php echo htmlspecialchars($client->name, ENT_QUOTES, 'UTF-8'); ?></h2>
                    <p class="mb-3">
                        Ez az oldal a következőket kérné a kozossegek.hu fiókodhoz:
                    </p>
                <?php endif; ?>
                <ul class="mb-4">
                    <li>Közösségek olvasása és kezelése</li>
                </ul>
                <p class="small text-muted mb-4">
                    Engedélyezés után a külső alkalmazás a nevedben kezelheti a közösségeidet.
                    A kapcsolatot bármikor visszavonhatod az <a href="@route('portal.integrations')">Integrációk</a> oldalon.
                </p>

                <form method="post" action="@route('oauth.decide')" class="d-flex flex-wrap gap-2">
                    @csrf
                    <input type="hidden" name="response_type" value="<?php echo htmlspecialchars($params['response_type'], ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="client_id" value="<?php echo htmlspecialchars($params['client_id'], ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="redirect_uri" value="<?php echo htmlspecialchars($params['redirect_uri'], ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="state" value="<?php echo htmlspecialchars($params['state'], ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="code_challenge" value="<?php echo htmlspecialchars($params['code_challenge'], ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="code_challenge_method" value="<?php echo htmlspecialchars($params['code_challenge_method'], ENT_QUOTES, 'UTF-8'); ?>">

                    <button type="submit" name="decision" value="allow" class="btn btn-orange rounded-pill px-4">
                        Engedélyezés
                    </button>
                    <button type="submit" name="decision" value="deny" class="btn btn-outline-secondary rounded-pill px-4">
                        Elutasítás
                    </button>
                </form>
            </div>
        </div>
    </div>

</mint-extend>
