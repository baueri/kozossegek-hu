
<!-- include summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/lang/summernote-hu-HU.min.js"></script>
<script>
    function initSummernote(selector, options)
    {
        options = $.extend({
            lang: 'hu-HU',
            tabsize: 2,
            height: 300,
            callbacks: {
                onImageUpload: function(files) {
                    sendFile(files[0], $(selector));
                },
                onDialogShown: function() {
                    if ($(".summernote-select-from-library").length === 0) {
                        $(".note-group-select-from-files").after("<div class='mb-3'><label>Médiatárból</label><br/><button class='btn btn-primary summernote-select-from-library' onclick='selectImageFromMediaLibrary()' type='button'>Médiatár</button></div>");
                    }
                }
            },
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        }, options);
        
        $(selector).summernote(options);
        
        function sendFile(file, editor, welEditable) {
            data = new FormData();
            data.append("file", file);
            $.ajax({
                data: data,
                type: "POST",
                url: "@route('admin.content.upload.upload_file')",
                cache: false,
                contentType: false,
                processData: false,
                success: function(url) {
                  editor.summernote('insertImage', url);
                }
            });
        }
        
    }
</script>