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
    
    var previousScroll = 0;

    $(window).scroll(function(){
       var currentScroll = $(this).scrollTop();
       if (currentScroll > previousScroll){
           
       } else {
          
       }
       previousScroll = currentScroll;
    });
    
});


$(window).on("load scroll", () => {
    if ($(window).scrollTop() > 0 || typeof window.orientation !== 'undefined') {
        $(".navbar").addClass("compact");
    } else {
        $(".navbar").removeClass("compact");
    }
});

$.fn.instituteSelect = function () {
    $(this).each(function(){
        $(this).select2({
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
        });
    });
    
    return $(this);
}

var loadFile = function(event, element) {
var id = $(element).data("target");
  var output = document.getElementById(id);
  output.src = URL.createObjectURL(event.target.files[0]);
  output.onload = function() {
    URL.revokeObjectURL(output.src) // free memory
  }
};


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
