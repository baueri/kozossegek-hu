$(() => {
    $('#main-finder select').select2();

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

        reader.onload = function(e) {
            $('#blah')
                .attr('src', e.target.result)
                .width(150)
                .height(200);
        };

        reader.readAsDataURL(input.files[0]);
    }
}