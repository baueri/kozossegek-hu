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
@extends('portal')
@featuredTitle($page_title)
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
                        <input type="text" class="form-control form-control-sm" name="name" required id="mail_name">
                    </div>
                    <div class="form-group required">
                        <label for="mail_address">Email címed</label>
                        <input type="email" class="form-control form-control-sm" name="email" required id="mail_address">
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
                        <textarea class="noresize form-control form-control-sm" name="message" rows="4" required onresize id="mail_msg"></textarea>
                    </div>
                    @honeypot('rolunk')
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
                });
            });
        });
    </script>
@endfooter
