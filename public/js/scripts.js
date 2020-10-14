$(() => {

    $(".img-big").on("load", function () {
        $(this).css("opacity", "1");
    });

    $(".kozi-kiskepek img").click(function () {
        $(".img-big").css("opacity", "0");
        var img = $(this);
        setTimeout(function(){
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

function detectmob() {
    return ((window.innerWidth <= 800) && (window.innerHeight <= 600));
}

function readURL(input) {

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
