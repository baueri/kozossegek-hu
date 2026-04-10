@header()
    <link rel="canonical" href="@route('portal.page', ['slug' => 'rolunk'])" />
    <meta name="description" content="Közösség rólunk, bemutatkozás" />
@endheader
@section('subtitle', 'Rólunk | ')
@section('scripts')
    @if($captchaEnabled)
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    @endif
@endsection
@extends('portal2026.portal')
@featuredTitle()
<h1 class="page-title">Rólunk</h1>
@endfeaturedTitle

<div class="rolunk-page">
    <div class="container inner py-4 py-md-5">
        <div class="rolunk-prose mb-4 mb-md-5">
            {{ $page->content }}
        </div>

        <section id="contact" class="rolunk-contact-section" aria-labelledby="rolunk-contact-heading">
            <div class="rolunk-contact-card">
                <div class="rolunk-contact-card-inner">
                    <aside class="rolunk-contact-aside">
                        <div>
                            <h2 id="rolunk-contact-heading mb-3">Írj nekünk!</h2>
                            <p class="rolunk-contact-lead">
                                Válaszolunk minden észrevételre és kérdésre. Válaszd ki a témát, és röviden fogalmazd meg, miben segíthetünk.
                            </p>
                        </div>
                        <img src="/images/rolunk.jpg" class="rolunk-contact-team img-fluid" width="640" height="480" alt="A kozossegek.hu közösségi portál" loading="lazy" />
                    </aside>
                    <div class="rolunk-contact-form-wrap">
                        <form method="post" id="send-message" action="@route('portal.contact_us')" novalidate>
                            <div class="form-row">
                                <div class="form-group col-md-6 required">
                                    <label for="mail_name">Neved</label>
                                    <input type="text" class="form-control" name="name" required id="mail_name" autocomplete="name">
                                </div>
                                <div class="form-group col-md-6 required">
                                    <label for="mail_address">Email címed</label>
                                    <input type="email" class="form-control" name="email" required id="mail_address" autocomplete="email">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="category">Mivel kapcsolatban keresel minket?</label>
                                <select id="category" name="category" class="form-control">
                                    <option value="kapcsolat">Kapcsolatfelvétel</option>
                                    <option value="honlap">Honlappal kapcsolatos kérdés, észrevétel</option>
                                </select>
                            </div>
                            <div class="form-group required">
                                <label for="mail_msg">Üzenet</label>
                                <textarea class="noresize form-control" name="message" rows="5" required id="mail_msg"></textarea>
                            </div>
                            @honeypot('rolunk')
                            @component('replay_attack', ['name' => 'contact'])
                            @if($captchaEnabled)
                                <div class="mb-3">
                                    @component('captcha')
                                </div>
                            @endif
                            <div class="rolunk-submit-wrap text-center text-md-left">
                                <button type="submit" name="send" class="btn btn-orange px-4 rounded-pill">
                                    <i class="fa fa-paper-plane mr-2" aria-hidden="true"></i>Üzenet elküldése
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@footer()
    <script>
        $(() => {
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
@endfooter
