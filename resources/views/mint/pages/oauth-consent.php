<mint-extend path="layout/inner.php" :subtitle="Alkalmazás csatlakoztatása | " :page-title="Alkalmazás csatlakoztatása" :inner-class="inner--auth">

    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
            <div class="auth-card">
                <h2 class="h4 mb-3"><?php echo htmlspecialchars($client->name, ENT_QUOTES, 'UTF-8'); ?></h2>
                <p class="mb-3">
                    Ez az alkalmazás a következőket kérné a kozossegek.hu fiókodhoz:
                </p>
                <ul class="mb-4">
                    <li>Közösségek olvasása és kezelése</li>
                </ul>
                <p class="small text-muted mb-4">
                    Engedélyezés után a partner alkalmazás a nevedben kezelheti a közösségeidet.
                    A kapcsolatot bármikor visszavonhatod a fiókodban.
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
