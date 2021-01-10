const dialog = (function () {

    let okBtn = function () {
        return {
            text: "Ok",
            cssClass: "btn btn-primary",
            action: (outer) => {
                return true;
            }
        }
    };

    let cancelBtn = function () {
        return {
            text: "Mégse",
            cssClass: "btn btn-default",
            action: (outer) => { return false; }
        }
    };

    this.closeAll = function () {
        $(".modal").modal("hide");
    }

    this.alertMessage = function (type, options, callback) {
        if (typeof options === "string") {
            return this.show({
                type: type,
                message: options
            }, callback);
        }

        this.show($.extend({
            type: type
        }, options), callback);
    }

    this.danger = function (options, callback) {
        this.alertMessage("danger", options, callback);
    }

    this.success = function (options, callback) {
        this.alertMessage("success", options, callback)
    }

    this.confirm = function (options, callback) {
        this.show($.extend({
            title: "Biztos vagy benne?",
            buttons: [
                $.extend(okBtn(), options.okBtn),
                $.extend(cancelBtn(), options.cancelBtn)
            ],
            callback: callback
        }, options));
    };

    this.show = function (options, callback) {

        var outer = $("<div class='modal fade' tabindex='-1'></div>");

        if (typeof options === "string") {
            options = { message: options, callback: callback };
        } else if (typeof options.callback === "undefined" && typeof callback !== "undefined") {
            options.callback = callback;
        }

        options = $.extend({
            title: "Információ",
            message: "",
            buttons: [okBtn()],
            callback: function (dialog) {
                dialog.modal("hide");
            },
            size: "lg",
            cssClass: "",
            onShown: null,
            type: null
        }, options);

        let headerCssClass = "";

        if (options.type) {
            headerCssClass = " bg-" + options.type + " text-light";
        }

        var dialog = $("<div class='modal-dialog modal-" + options.size + " " + options.cssClass + "'></div>");
        var content = $("<div class='modal-content'></div>");
        var header = $("<div class='modal-header" + headerCssClass + "'><h5 class='modal-title'>" + options.title + "</h5><button type='button' class='close' data-dismiss='modal' aria-label='bezár'><span aria-hidden='true'>&times;</span></button></div>");
        var body = $("<div class='modal-body'></div>");

        body.append(options.message);

        var footer = $("<div class='modal-footer'></div>");

        if (options.buttons) {
            for (var i in options.buttons) {
                let button = options.buttons[i];
                var btnDOM = $('<button type="button" class="' + button.cssClass + '">' + button.text + '</button>');
                btnDOM.click(function () {
                    options.callback(outer, button.action(outer));
                });
                footer.append(btnDOM);
            }
        }

        content.append(header).append(body).append(footer);
        dialog.append(content);
        outer.append(dialog);

        outer.on("show.bs.modal", function () {
            let idx = $('.modal:visible').length;
            $(this).css('z-index', 1040 + (10 * idx));
        });

        outer.on("shown.bs.modal", function () {
            let idx = ($('.modal:visible').length) - 1; // raise backdrop after animation.
            $('.modal-backdrop').not('.stacked').css('z-index', 1039 + (10 * idx));
            $('.modal-backdrop').not('.stacked').addClass('stacked');
        })

        if (options.onShown) {
            outer.on("shown.bs.modal", function () {
                options.onShown(outer);
            });
        }

        outer.on("hidden.bs.modal", function () {
            $(this).remove();
        });

        $("body").append(outer);

        outer.modal('show');
    };

    return this;
})();

$(() => {
    $(".confirm-action").on("click", function (e) {
        const action = $(this).attr("href");
        e.preventDefault();
        dialog.confirm("Biztos vagy benne?", function (dialog, confirmed) {

        });
    });

});
