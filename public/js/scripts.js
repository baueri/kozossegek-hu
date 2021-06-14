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

    $("[title]").tooltip({
        trigger: "hover"
    });
    setAgeGroupClass();
    $("#search-group select").on("change", function () {
        setAgeGroupClass();
    });
});

function setAgeGroupClass()
{
    const sel = $("#search-group select");
    if (sel.val()) {
        sel.addClass("has-val");
    } else {
        sel.removeClass("has-val");
    }
}

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
            placeholder: "kezdd el gépelni az intézmény nevét...",
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

//cookie
(function () {
    "use strict";

    var cookieAlert = document.querySelector(".cookiealert");
    var acceptCookies = document.querySelector(".acceptcookies");

    if (!cookieAlert) {
        return;
    }

    cookieAlert.offsetHeight; // Force browser to trigger reflow (https://stackoverflow.com/a/39451131)

    // Show the alert if we cant find the "acceptCookies" cookie
    if (!getCookie("acceptCookies")) {
        cookieAlert.classList.add("show");
    }

    // When clicking on the agree button, create a 1 year
    // cookie to remember user's choice and close the banner
    acceptCookies.addEventListener("click", function () {
        setCookie("acceptCookies", true, 365);
        cookieAlert.classList.remove("show");

        // dispatch the accept event
        window.dispatchEvent(new Event("cookieAlertAccept"))
    });

    // Cookie functions from w3schools
    function setCookie(cname, cvalue, exdays)
    {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function getCookie(cname)
    {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) === ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) === 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }
})();


function showLoginModal(redirectUrlAfterLogin)
{
    $.post("/login-modal", {redirect: redirectUrlAfterLogin}, html => {
        dialog.show({
            size: "sm",
            type: "info",
            title: "Belépés",
            message: html,
            buttons: [
                {
                    text: "Bezár",
                    cssClass: "btn btn-default",
                    action(dialog) {
                        dialog.close();
                    }
            }
            ]
        });
    });
}

function resendActivationEmail(emailAddress)
{
    $.post("/resend-activation", {email: emailAddress}, response => {
        if (response.success) {
            dialog.success({
                size: "md",
                message: "Az aktivációs email sikeresen elküldve"
            });
        } else {
            dialog.danger({
                size: "md",
                message: response.msg ? response.msg : "Váratlan hiba történt"
            });
        }
    });
}
