$.fn.citySelect = function (options) {

    $(this).select2($.extend({
        placeholder: "város",
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

function notify(msg, type) {
    var DOM = $("<div class='notification alert alert-"+type+"'>" + msg + "</div>");
    DOM.appendTo("#content").slideToggle("fast");
    DOM.click(function(){dismissNotification(DOM)});
    setTimeout(function() {
        dismissNotification(DOM);
    }, 3500);
}

function dismissNotification(DOM) {
    DOM.slideUp();
    setTimeout(function() { DOM.remove() }, 1000);
}
