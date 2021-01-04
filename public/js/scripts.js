$(() => {

    $(".img-big").on("load", function () {
        $(this).css("opacity", "1");
    });

    $(".kozi-kiskepek img").click(function () {
        $(".img-big").css("opacity", "0");
        var img = $(this);
        setTimeout(function () {
            $(".img-big").attr("src", img.attr("src"));
        }, 200);
    });

    $("[title]").tooltip();
});


$(window).on("load scroll", () => {
    if ($(window).scrollTop() > 0 || typeof window.orientation !== 'undefined') {
        $(".navbar").addClass("compact");
    } else {
        $(".navbar").removeClass("compact");
    }
});

$.fn.instituteSelect = function (options) {
    $(this).each(function () {
        $(this).select2($.extend({
            placeholder: "intézmény",
            allowClear: true,
            ajax: {
                url: "/api/v1/search-institute",
                dataType: 'json',
                delay: 300,
                data: function (params) {
                    params.city = $("[name=city]").val();
                    return params;
                }
            }
        }, options));
    });

    return $(this);
}

$.fn.inputMessage = function (action, message, type) {

    let inputClass, messageClass;

    if (type === "danger") {
        inputClass = "is-invalid";
        messageClass = "invalid-feedback";
    } else if (type === "success") {
        inputClass = "is-valid";
        messageClass = "valid-feedback";
    }

    $(this).each(function () {
        const messageDiv = $(this).next(".validate_message");
        if (action === "dismiss") {
            $(this).removeClass("is-invalid is-valid");
            messageDiv.removeClass("invalid-feedback valid-feedback");
            messageDiv.html("");
        } else if (typeof action === "undefined" || !action || action === "show") {
            $(this).addClass(inputClass)
            messageDiv.addClass(messageClass);
            if (typeof message !== "undefined") {
                messageDiv.html(message);
            }
        }
    });

    return $(this);
};

$.fn.inputError = function (action, message) {

    $(this).inputMessage(action, message, "danger");

    return $(this);
};

$.fn.inputOk = function (action, message) {

    $(this).inputMessage(action, message, "success");

    return $(this);
};

const loadFile = function (event, element) {
    const id = $(element).data("target");
    const output = document.getElementById(id);
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function () {
        URL.revokeObjectURL(output.src) // free memory
    }
};


function detectmob()
{
    return ((window.innerWidth <= 800) && (window.innerHeight <= 600));
}

function readURL(input)
{

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#blah')
                .attr('src', e.target.result)
                .width(150)
                .height(200);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

function validate_email(mail)
{
    return /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(mail);
}
