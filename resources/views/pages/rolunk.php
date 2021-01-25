@header()
    <style>
        #send-message label {
            margin-bottom: .2rem;
        }
    </style>
@endheader
@section('header_content')
    @featuredTitle($page_title)
@endsection
@extends('portal')
<div class="container inner p-4 page">
    <div>
        {{ $page->content }}
        <span id="contact"></span>
    </div>
</div>
<div class="jumbotron main-block mt-0 mb-0">
    <div class="container">
        <div class="row">
            <div class="col-md-5 offset-2lehet">
                <img src="/images/csoportkep_contact.jpg"/>
            </div>
            <div class="col-md-5">
                <h4>Írj nekünk!</h4>
                <form method="post" id="send-message" action="@route('api.portal.contact_us')">
                    <div class="form-group required">
                        <label class="" for="mail_name">Neved</label>
                        <input type="text" class="form-control form-control-sm" name="name" required id="mail_name">
                    </div>
                    <div class="form-group required">
                        <label for="mail_address">Email címed</label>
                        <input type="email" class="form-control form-control-sm" name="email" required id="mail_address">
                    </div>
                    <div class="form-group required">
                        <label for="mail_msg">Üzenet</label>
                        <textarea class="noresize form-control form-control-sm" name="message" rows="4" required onresize id="mail_msg"></textarea>
                    </div>
                    @honeypot()
                    <button type="submit" name="send" class="btn btn-primary btn-sm"><i class="fa fa-paper-plane mr-2"></i> Üzenet elküldése</button>
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

                        window.onbeforeunload = function () {
                            window.scrollTo(0, 0);
                        }

                        dialog.success(response.msg, () => { window.location.href; })
                    }
                });
            });
        });
    </script>
@endfooter
