/// <reference path="jquery-1.10.2.min.js"/>
$(document).ready(function () {
    function getAllQuery() {
        var search = location.search.substring(1);
        var arr = search.split('&'), all = {};
        arr.forEach(function (str) {
            var t = str.split('=');
            this[t[0]] = decodeURIComponent(t[1] || '');
        }, all);
        return all;
    }
    function createQuery(data) {
        var arr = [];
        for (var key in data) {
            if (data.hasOwnProperty(key) && typeof (data[key]) !== 'function') {
                arr.push(key + '=' + encodeURIComponent(data[key]));
            }
        }
        return arr.join('&');
    }
    var script = {
        diancan: [
          {
              url: '/user.php?username=test&password=111111&act=act_login'
          }
        ]
    };
    function init() {
        $('body').append('<div id="help-mask"><a class="exit">退出帮助</a><div class="help-list"><a data-fun="diancan">点餐演示</a></div><div class="help-cur"></div></div>');
        $('#help-mask').hide();
        $('#help-mask>a.exit').click(hide);
    }
    function hide() {
        var $mask = $('#help-mask');
        $mask.stop().fadeOut(600);
    }
    function help() {
        var $mask = $('#help-mask');
        if (!$mask.size()) {
            init();
            $mask = $('#help-mask')
        }
        $mask.height($('body').outerHeight());
        $mask.fadeIn(300);
    }
    $('#want-help').click(help);
    function help_auto() {
    }
});