function pixflow_tabShortcode() {
    "use strict";
    $('body').off('click', '.px_tabs_nav li.ui-state-default');
    $('body').on('click', '.px_tabs_nav li.ui-state-default', function () {
        setTimeout(function () {
            $(window).resize();
        }, 150);
    });
    $("ul.md-custom-tab > li").click(function () {
        setTimeout(function () {
            $(window).resize();
        }, 150);
    });
}
document_ready_functions.pixflow_tabShortcode = [];