<!doctype html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email minta</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        body {
            background: #fafafa;
        }
        body #mail-content {
            max-width: 880px;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 40px;
            margin: auto;
            overflow: hidden;
            background: #fff;
            box-sizing: border-box;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="/js/admin.js"></script>
    @include('asset_groups.editor')
</head>
<body>
<h3 class="text-center">
    {{ $title }}
</h3>
<div id="mail-content">
    {{ $mailable->getBody() }}
</div>
<p style="max-width: 880px; margin: auto;">
    <a href="@route('admin.email_template.list')" style="color: #888;">vissza a sablonokhoz</a>
    <a href="#" onclick="return false;" class="float-right" id="edit-email-template" style="color: #888">szöveg szerkesztése</a>
    <a href="#" onclick="return false;" class="float-right" id="dispose-email-template-editor" style="color: #888; display: none;">mégse</a>
</p>
<div class="modal fade" id="editor-modal" tabindex="-1" role="dialog" aria-labelledby="editorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editorModalLabel">Sablon szerkesztése</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @alert('info')
                    <p>
                        <i class="fa fa-exclamation-circle"></i> <b>Figyelem!</b> A tartalom első sorát - <b>&commat;extends('mail.wrapper')</b> - ne töröld és ne módosítsd, ez állítja be a fej- illetve a láblécet!
                    </p>
                    <p>Az alább felsorolt dinamikus változókat az email szövegében tudod felhasználni. Az, hogy egy ilyen változó mit tartalmaz, annak nevéből lehet kikövetkeztetni, de ha mégse tudod, kérdezd meg a honlap fejlesztőjét.</p>
                    Felhasználható dinamikus változók:
                    <ul>
                        <?php foreach($mailable->getVariableNames() as $variable): ?>
                            <li>&lbrace;&lbrace; ${{ $variable }} &rbrace;&rbrace;</li>
                        <?php endforeach; ?>
                    </ul>
                @endalert
                <textarea name="email-content">{{ file_get_contents(view()->getPath($mailable->getView())) }}</textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Bezár</button>
                <button type="button" id="save-content" class="btn btn-primary">Mentés</button>
            </div>
        </div>
    </div>
</div>
<script>
    $("[name='email-content']").summernote({
        lang: "hu-HU",
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });
    $("#edit-email-template").click(function(){
        $("#editor-modal").modal("show");
    });
    $("#save-content").click(function(){
        var content = $("[name='email-content']").val();
        $.post("@route('admin.email_template.save_template')", {
            template: "{{ $mailable->getView() }}",
            content: content,
        }, function (response) {
            if(response.success) {
                dialog.closeAll();
                dialog.show("Sikeres mentés", function () {
                    window.location.reload();
                });
            }
        });
    });
</script>
</body>
</html>
