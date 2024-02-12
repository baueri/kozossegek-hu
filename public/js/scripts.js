$(() => {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
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

    $(".navbar-nav > .nav-item > a").click(function () {
        $(this).closest(".nav-item").toggleClass("open");
        const submenu = $("> .submenu", $(this).closest(".nav-item"));
        if (submenu.length) {
            // submenu.toggleClass("open");
        }
    });

    $("body").click((e) => {
        if (!$(e.target).closest(".nav-item").length && $(e.target).attr("id") !== "login-existing-user" && $(e.target).closest("#login-existing-user").length === 0) {
            $(".nav-item").removeClass("open");
        }
    });


    let search;
    let search_results = $(".search-results");

    $("input[name=search]").on("keyup", function(e) {
        console.log(e.key);
        if (e.key === 'Escape') {
            search_results.hide();
            return;
        } else if (e.key === 'ArrowDown') {
            if ($(".group-result.selected", search_results).length === 0) {
                $(".group-result", search_results).first().addClass("selected");
            } else {
                $(".group-result.selected", search_results).removeClass("selected").next().addClass("selected");
            }
            return;
        } else if (e.key === 'ArrowUp') {
            if ($(".group-result.selected", search_results).length === 0) {
                $(".group-result", search_results).last().addClass("selected");
            } else {
                $(".group-result.selected", search_results).removeClass("selected").prev().addClass("selected");
            }
            return;
        }

        let $this = $(this);
        if ($this.val().length < 3) {
            return;
        }

        clearTimeout(search);

        search = setTimeout(() => {
            const q = $this.val();
            const age_group = $("[name=korosztaly]").val();
            $.get($this.attr("data-url"), { q, age_group }, function(resp) {
                if (resp.result.length > 0) {
                    $(".search-results-inner", search_results).html(resp.result);
                    search_results.show();
                } else {
                    search_results.hide()
                }
            });
        }, 300)
    }).on("keydown", function (e) {
        if (e.key === 'Enter' && $(".group-result.selected", search_results).length > 0) {
            window.location.href = $(".group-result.selected", search_results).attr("href");
            e.preventDefault();
        }
    }).on("focusout", () => setTimeout(() => search_results.hide(), 300)).on("focus", () => {

    });
});

$(window).on("load scroll", () => {
    if ($(window).scrollTop() > 0 || typeof window.orientation !== 'undefined') {
        $(".navbar").addClass("compact");
    } else {
        $(".navbar").removeClass("compact");
    }
});

$(window).on("resize", () => {
    mobile_menu("close");
})

function mobile_menu(action) {
    $("#toggle_main_menu").prop("checked", action === "open")
}

function setAgeGroupClass()
{
    const sel = $("#search-group select");
    if (sel.val()) {
        sel.addClass("has-val");
    } else {
        sel.removeClass("has-val");
    }
}

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
    return (window.innerWidth <= 991);
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

    // Show the alert if we can't find the "acceptCookies" cookie
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
    if (detectmob()) {
        mobile_menu("open");
        $("#popup-login-username").focus();
    } else {
        $("label[for='popup-login-username']").closest(".nav-item").addClass("open");
    }
    return;
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


document.addEventListener("DOMContentLoaded", function() {
    let lazyImages = [].slice.call(document.querySelectorAll("img.lazy"));

    if ("IntersectionObserver" in window) {
        let lazyImageObserver = new IntersectionObserver(function(entries, observer) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    let lazyImage = entry.target;
                    lazyImage.src = lazyImage.dataset.src;
                    lazyImage.srcset = lazyImage.dataset.srcset;
                    lazyImage.classList.remove("lazy");
                    lazyImageObserver.unobserve(lazyImage);
                }
            });
        });

        lazyImages.forEach(function(lazyImage) {
            lazyImageObserver.observe(lazyImage);
        });
    } else {
        // Possibly fall back to event handlers here
    }
});