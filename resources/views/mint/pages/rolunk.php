<mint-extend path="layout/inner.php" :subtitle="Rólunk | " :page-title="Rólunk">

    <mint-section name="header">
        <link rel="canonical" href="@route('portal.page', ['slug' => 'rolunk'])" />
        <meta name="description" content="Közösség rólunk, bemutatkozás" />
        <script x:if="{$captchaEnabled}" src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    </mint-section>

    <mint-section name="scripts">
        <script>
            $(() => {
                const params = new URLSearchParams(window.location.search);
                const prefillMessage = params.get('message');
                if (prefillMessage) {
                    const $msg = $('#mail_msg');
                    if ($msg.length && !$msg.val().trim()) {
                        $msg.val(prefillMessage);
                    }
                }

                $("#send-message").submit(function (e) {
                    e.preventDefault();

                    $.post($(this).attr("action"), $(this).serialize(), response => {
                        if (!response.success) {
                            dialog.danger(response.msg);
                        } else {
                            dialog.success({
                                message: response.msg,
                                size: "md"
                            }, () => { window.location.reload() })
                        }
                    }).fail(function (response) {
                        if (response.responseJSON.err_code === 'captcha_failed') {
                            dialog.danger(response.responseJSON.msg, () => {
                                dialog.closeAll();
                                turnstile.reset(cf_wid)
                            });
                        } else {
                            dialog.danger(response.responseJSON.msg);
                        }
                    });
                });
            });
        </script>
    </mint-section>

    <div class="rolunk-page">
        <div class="rolunk-prose mb-4 mb-md-5">
            {{ $page->content }}
        </div>

        <section id="contact" class="rolunk-contact-section" aria-labelledby="rolunk-contact-heading">
            <div class="rolunk-contact-card">
                <div class="rolunk-contact-card-inner">
                    <aside class="rolunk-contact-aside">
                        <div>
                            <h2 id="rolunk-contact-heading" class="mb-3">Írj nekünk!</h2>
                            <p class="rolunk-contact-lead">
                                Válaszolunk minden észrevételre és kérdésre. Válaszd ki a témát, és röviden fogalmazd meg, miben segíthetünk.
                            </p>
                        </div>
                        <img src="/images/rolunk.jpg" class="rolunk-contact-team img-fluid" width="640" height="480" alt="A kozossegek.hu közösségi portál" loading="lazy" />
                    </aside>

                    <div class="rolunk-contact-form-wrap">
                        <form method="post" id="send-message" action="@route('portal.contact_us')" novalidate>
                            <div class="row g-3">
                                <div class="col-md-6 mb-3 required">
                                    <label for="mail_name">Neved</label>
                                    <input type="text" class="form-control" name="name" required id="mail_name" autocomplete="name">
                                </div>
                                <div class="col-md-6 mb-3 required">
                                    <label for="mail_address">Email címed</label>
                                    <input type="email" class="form-control" name="email" required id="mail_address" autocomplete="email">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="category">Mivel kapcsolatban keresel minket?</label>
                                <select id="category" name="category" class="form-select">
                                    <option value="kapcsolat">Kapcsolatfelvétel</option>
                                    <option value="honlap">Honlappal kapcsolatos kérdés, észrevétel</option>
                                </select>
                            </div>
                            <div class="mb-3 required">
                                <label for="mail_msg">Üzenet</label>
                                <textarea class="noresize form-control" name="message" rows="5" required id="mail_msg"></textarea>
                            </div>

                            <mod-honeypot :id="rolunk" />
                            <mod-replay-attack :name="contact" />
                            @csrf

                            <div x:if="{$captchaEnabled}" class="mb-3">
                                <mod-captcha />
                            </div>

                            <div class="rolunk-submit-wrap text-center text-md-start">
                                <button type="submit" name="send" class="btn btn-orange px-4 rounded-pill">
                                    <i class="fa fa-paper-plane me-2" aria-hidden="true"></i>Üzenet elküldése
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>

</mint-extend>
