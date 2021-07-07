const dialog = (function () {

    let thisDialog = this;

    let okBtn = function () {
        return {
            text: "Ok",
            cssClass: "btn btn-primary",
            action(modal, callback) { callback(modal, true); }
        }
    };

    let cancelBtn = function () {
        return {
            text: "Mégse",
            cssClass: "btn btn-default",
            action(modal, callback) { callback(modal, false); }
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

        let ok = okBtn();
        let cancel = cancelBtn();

        this.show($.extend({
            title: "Biztos vagy benne?",
            buttons: [
                ok,
                cancel
            ],
            callback: callback
        }, options));
    };

    this.show = function (options, callback) {

        if (typeof options === "string") {
            options = { message: options, callback: callback };
        } else if (typeof options.callback === "undefined" && typeof callback !== "undefined") {
            options.callback = callback;
        }

        options = $.extend({
            title: "Információ",
            message: "",
            buttons: [okBtn()],
            callback: function (modal) {
                modal.close();
            },
            size: "lg",
            cssClass: "",
            onShown: null,
            type: null,
            draggable: false,
            closable: true
        }, options);

        if (options.okBtn) {
            options.buttons[0] = $.extend(options.buttons[0], options.okBtn);
        }

        if (options.cancelBtn) {
            options.buttons[1] = $.extend(options.buttons[1], options.cancelBtn);
        }

        let backDrop = !options.closable ? "data-backdrop='static' data-keyboard='false' tabindex='-1'" : "";

        let outer = $("<div class='modal fade' " + backDrop + " tabindex='-1'></div>");

        let headerCssClass = "";

        if (options.type) {
            headerCssClass = " bg-" + options.type + " text-light";
        }

        let dialog = $("<div class='modal-dialog modal-" + options.size + " " + options.cssClass + "'></div>");
        let content = $("<div class='modal-content'></div>");
        let closableBtn = options.closable ? "<button type='button' class='close' data-dismiss='modal' aria-label='bezár'><span aria-hidden='true'>&times;</span></button>" : "";
        let header = $("<div class='modal-header" + headerCssClass + "'><h5 class='modal-title'>" + options.title + "</h5>" + closableBtn + "</div>");
        let body = $("<div class='modal-body'></div>");

        if (options.draggable) {
            content.draggable({handle:header});
        }

        body.append(options.message);

        let footer = $("<div class='modal-footer'></div>");

        if (options.buttons) {
            for (let i in options.buttons) {
                let button = options.buttons[i];
                let btnDOM = $('<button type="button" class="' + button.cssClass + '">' + button.text + '</button>');
                btnDOM.click(function () {
                    button.action(outer, options.callback);
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

        outer.close = function () {
            $(this).modal("hide");
        }

        outer.closeAll = function () {
            thisDialog.closeAll();
        }
    };

    return this;
})();

$(() => {
    $(document).on("click", ".confirm-action", function (e) {
        const action = $(this).attr("href");
        let message = $(this).data("confirm_message");

        if (!message) {
            message = "Biztos vagy benne?";
        }

        e.preventDefault();

        dialog.confirm({message: message, type: "danger", size: "md"}, function (modal, confirmed) {
            if (confirmed) {
                window.location.href = action;
            } else {
                modal.close();
            }
        });
    });

});

$.fn.confirm = function (options) {
    options = $.extend({
        title: "Biztos vagy benne?",
        message: "",
        isAjax: false,
        afterResponse(response) {  }
    }, options);

    $(this).each(function () {
        $(this).click(function (e) {
            let action = $(this).attr("href");
            let onConfirm;
            if (options.isAjax) {
                onConfirm = () => {
                    $.post(action, options.afterResponse);
                }
            } else {
                onConfirm = () => { window.location.href = action };
            }

            e.preventDefault();
            dialog.confirm(options, function (d, confirmed) {
                if (confirmed) {
                    onConfirm();
                } else {
                    d.close();
                }
            });
        });
    });
}

