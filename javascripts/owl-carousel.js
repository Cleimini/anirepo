$(function() {
    $(".owl-carousel").owlCarousel({
        dots: false,
        loop: true,
        margin: 15,
        nav: false,

        responsive:{
            0: {
                items: 1
            },

            678: {
                items: 3
            },

            1000: {
                items: 5
            }
        }
    });
});