$.fn.citySelect = function (options) {
    $(this).select2($.extend({
        placeholder: "település",
        allowClear: true,
        tags: true,
        ajax: {
            url: '/api/v1/search-city',
            dataType: 'json',
            delay: 300
        }
    }, options));

    return this;
}

$.fn.baseSelect = function (url, options) {
    $(this).select2($.extend({
        placeholder: "",
        allowClear: true,
        tags: true,
        ajax: {
            url: url,
            dataType: 'json',
            delay: 300
        }
    }, options));

    return this;
}

$.fn.districtSelect = function (options) {
    $(this[0]).select2($.extend({
        placeholder: "városrész",
        allowClear: true,
        tags: true,
        ajax: {
            url: '/api/v1/search-district',
            dataType: 'json',
            delay: 300,
            data: function (params) {
                if (typeof options !== "undefined" && typeof options.city_selector !== "undefined") {
                    var city;
                    if (city = $(options.city_selector).val()) {
                        params.city = city;
                    }
                }
                return params;
            }
        }
    }, options));

    return this;
}


function notify(msg, type)
{
    var DOM = $("<div class='notification alert alert-" + type + "'>" + msg + "</div>");
    DOM.appendTo("#content").slideToggle("fast");
    DOM.click(function () {
        dismissNotification(DOM)
    });
    setTimeout(function () {
        dismissNotification(DOM);
    }, 3500);
}

function dismissNotification(DOM)
{
    DOM.slideUp();
    setTimeout(function () {
        DOM.remove()
    }, 1000);
}

var loadFile = function (event, element) {
    var id = $(element).data("target");
    var output = document.getElementById(id);
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function () {
        URL.revokeObjectURL(output.src) // free memory
    };
};

function deleteConfirm(action)
{
    var outer = $("<div class='modal fade' tabindex='-1'></div>");
    var dialog = $("<div class='modal-dialog'></div>");
    var content = $("<div class='modal-content'></div>");
    var header = $("<div class='modal-header'><h5 class='modal-title'>Biztos?</h5><button type='button' class='close' data-dismiss='modal' aria-label='bezár'><span aria-hidden='true'>&times;</span></button></div>");
    var body = $("<div class='modal-body'>Biztosan törlöd?</div>");
    var cancelBtn = $('<button type="button" class="btn btn-default" data-dismiss="modal">Mégsem</button>');
    var okBtn = $('<button type="button" class="btn btn-danger">Igen</button>');
    var footer = $("<div class='modal-footer'></div>");

    okBtn.click(action);

    footer.append(cancelBtn).append(okBtn);
    content.append(header).append(body).append(footer);

    dialog.append(content);
    outer.append(dialog);

    outer.modal('show');
}

function selectImageFromMediaLibrary(options)
{
    console.log(options);
    $.post("/admin/api/uploads/get", {dir: options.dir}, response => {
        dialog.show({
            title: "Feltöltések",
            message: response,
            size: "xl",
            cssClass: "uploads-modal",
            onShown: function (dialog) {
                $("#modal-uploads .file-item").click(function (e) {
                    e.preventDefault();

                    if ($(this).hasClass("item-dir")) {
                        dialog.modal("hide");
                        options.dir = $(this).data("text");
                        selectImageFromMediaLibrary(options);
                    } else {
                        var src = $(this).data("src");
                        var is_img = $(this).data("is_img");
                        var text = $(this).data("text");
                        options.onSelect({
                            text: text,
                            is_img: is_img,
                            src: src
                        });
                        dialog.modal('hide');
                    }
                });
            }
        });
    });
}

$(() => {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});