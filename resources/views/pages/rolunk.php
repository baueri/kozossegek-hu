@header()
    <link rel="canonical" href="@route('portal.page', ['slug' => 'rolunk'])" />
    <meta name="description" content="Közösség rólunk, bemutatkozás" />
    <style>
        #send-message label {
            margin-bottom: .2rem;
        }
    </style>
@endheader
@section('subtitle', 'Rólunk | ')
@section('scripts')
    @if($captchaEnabled)
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    @endif
@endsection
@extends('portal')
@featuredTitle('Rólunk')
<div class="container inner p-4 page">
    {{ $page->content }}
    <span id="contact"></span>
    <div class="card shadow p-3">
        <div class="row">
            <div class="col-md-6 text-center mb-3">
                <img src="/images/csoportkep_contact.jpg" alt="A kozossegek.hu csapata"/>
            </div>
            <div class="col-md-6">
                <h4>Írj nekünk!</h4>
                <form method="post" id="send-message" action="@route('portal.contact_us')">
                    <div class="form-group required">
                        <label class="" for="mail_name">Neved</label>
                        <input type="text" class="form-control" name="name" required id="mail_name">
                    </div>
                    <div class="form-group required">
                        <label for="mail_address">Email címed</label>
                        <input type="email" class="form-control" name="email" required id="mail_address">
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
                        <textarea class="noresize form-control" name="message" rows="4" required onresize id="mail_msg"></textarea>
                    </div>
                    @honeypot('rolunk')
                    @component('replay_attack', ['name' => 'contact'])
                    @if($captchaEnabled)
                    <div class="mb-1">
                        @component('captcha')
                    </div>
                    @endif
                    <p class="text-center">
                        <button type="submit" name="send" class="btn btn-altblue rounded-pill shadow"><i class="fa fa-paper-plane mr-2"></i> Üzenet elküldése</button>
                    </p>
                </form>
            </div>
        </div>
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
