/* 修补程序 */
(function () {
    $(document).ready(function () {
        $('[data-btn-classes]').each(function () {
            var t = $(this);
            var cssList = t.attr('data-btn-classes').split(' ');
            var last = cssList[cssList.length - 1];
            t.find('a').addClass(function (i) {
                return 'mx-btn ' + cssList[i] || last;
            });
        });
    });
})();