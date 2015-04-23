(function ($) {
    function navAnimate() {
        var nav = $(".main-nav"), nav_items = $(".item", nav);
        nav_items.each(function (k, v) {
            var self = $(this);
            setTimeout(function () {
                self.css({"-webkit-transform": "translate3d(0, 0, 0)", "-moz-transform": "translate3d(0, 0, 0)", "-ms-transform": "translate3d(0, 0, 0)", "transform": "translate3d(0, 0, 0)"})
            }, 600);
        })
    }
    navAnimate();
})(Zepto);
