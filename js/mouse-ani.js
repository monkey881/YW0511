/// <reference path="jquery-1.10.2.min.js"/>
$(document).ready(function () {
    $(document).delegate('.ani_1', 'mouseenter', function (e) {
        var t = $(e.currentTarget);
        t.animate({ left: '+=3' }, 60);
        t.animate({ top: '+=3' }, 60);
        t.animate({ left: '-=3' }, 60);
        t.animate({ top: '-=3' }, 60);
    });
    $(document).delegate('.ani_brand_item', 'mouseenter', function (e) {
        var t = $(e.currentTarget);
        if (t.find('.d1>img').size() < 2) return;
        t.find('.d1>img.logo_main').stop().fadeOut(300);
    }).delegate('.ani_brand_item', 'mouseleave', function (e) {
        var t = $(e.currentTarget);
        if (t.find('.d1>img').size() < 2) return;
        t.find('.d1 img.logo_main').stop().fadeIn(300);
    });
});